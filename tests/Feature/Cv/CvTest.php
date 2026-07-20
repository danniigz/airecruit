<?php

namespace Tests\Feature\Cv;

use App\Models\Cv;
use App\Models\User;
use App\Services\OpenAIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CvTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_a_cv(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('mi_cv.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->postJson(route('api.cvs.store'), [
            'cv' => $file,
        ]);

        $response->assertCreated();
        $response->assertJsonPath('cv.original_filename', 'mi_cv.pdf');

        $this->assertDatabaseHas('cvs', [
            'user_id' => $user->id,
            'original_filename' => 'mi_cv.pdf',
        ]);

        $cv = Cv::firstOrFail();
        Storage::disk('local')->assertExists($cv->file_path);
    }

    public function test_cv_upload_requires_a_pdf_file(): void
    {
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('mi_cv.txt', 10, 'text/plain');

        $response = $this->actingAs($user)->postJson(route('api.cvs.store'), [
            'cv' => $file,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('cv');
    }

    public function test_user_can_analyze_their_cv_with_mocked_ai(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $path = 'cvs/'.$user->id.'/test-cv.pdf';
        Storage::disk('local')->put($path, $this->fakePdfContent());

        $cv = Cv::factory()->for($user)->create([
            'file_path' => $path,
        ]);

        $analysis = [
            'resumen' => 'Desarrollador backend con experiencia en Laravel.',
            'puntos_fuertes' => ['PHP', 'Laravel', 'APIs REST'],
            'areas_mejora' => ['Testing automatizado'],
            'anos_experiencia_aproximados' => 4,
            'skills_principales' => ['PHP', 'Laravel', 'MySQL'],
        ];

        $this->mock(OpenAIService::class, function ($mock) use ($analysis): void {
            $mock->shouldReceive('askForJson')->once()->andReturn($analysis);
        });

        $response = $this->actingAs($user)->postJson(route('api.cvs.analyze', $cv));

        $response->assertOk();
        $response->assertJsonPath('analysis.resumen', $analysis['resumen']);

        $cv->refresh();
        $this->assertSame($analysis, $cv->ai_analysis);
        $this->assertNotNull($cv->analyzed_at);
    }

    private function fakePdfContent(): string
    {
        $text = 'BT /F1 12 Tf 50 750 Td (Curriculum de prueba para tests automatizados.) Tj ET';

        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            2 => '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            3 => '<< /Type /Page /Parent 2 0 R /Resources << /Font << /F1 4 0 R >> >> /MediaBox [0 0 612 792] /Contents 5 0 R >>',
            4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            5 => "<< /Length ".strlen($text)." >>\nstream\n{$text}\nendstream",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $number => $body) {
            $offsets[$number] = strlen($pdf);
            $pdf .= "{$number} 0 obj\n{$body}\nendobj\n";
        }

        $xrefStart = strlen($pdf);
        $count = count($objects) + 1;

        $pdf .= "xref\n0 {$count}\n0000000000 65535 f \n";
        foreach ($offsets as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        $pdf .= "trailer\n<< /Size {$count} /Root 1 0 R >>\nstartxref\n{$xrefStart}\n%%EOF";

        return $pdf;
    }
}
