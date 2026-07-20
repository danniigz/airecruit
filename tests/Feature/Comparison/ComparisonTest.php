<?php

namespace Tests\Feature\Comparison;

use App\Models\Cv;
use App\Models\JobOffer;
use App\Models\User;
use App\Services\OpenAIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComparisonTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_compare_their_cv_with_a_job_offer_using_mocked_ai(): void
    {
        $user = User::factory()->create();
        $cv = Cv::factory()->for($user)->create();
        $jobOffer = JobOffer::factory()->for($user)->create();

        $aiResult = [
            'puntuacion_compatibilidad' => 82,
            'fortalezas' => ['Experiencia relevante en Laravel'],
            'carencias' => ['Falta experiencia con Kubernetes'],
            'recomendaciones' => ['Destacar proyectos con APIs REST'],
        ];

        $this->mock(OpenAIService::class, function ($mock) use ($aiResult): void {
            $mock->shouldReceive('askForJson')->once()->andReturn($aiResult);
        });

        $response = $this->actingAs($user)->postJson(route('api.comparisons.store'), [
            'cv_id' => $cv->id,
            'job_offer_id' => $jobOffer->id,
        ]);

        $response->assertCreated();
        $response->assertJsonPath('comparison.compatibility_score', 82);

        $this->assertDatabaseHas('comparisons', [
            'user_id' => $user->id,
            'cv_id' => $cv->id,
            'job_offer_id' => $jobOffer->id,
            'compatibility_score' => 82,
        ]);
    }

    public function test_compatibility_score_is_clamped_between_0_and_100(): void
    {
        $user = User::factory()->create();
        $cv = Cv::factory()->for($user)->create();
        $jobOffer = JobOffer::factory()->for($user)->create();

        $this->mock(OpenAIService::class, function ($mock): void {
            $mock->shouldReceive('askForJson')->once()->andReturn([
                'puntuacion_compatibilidad' => 150,
                'fortalezas' => [],
                'carencias' => [],
                'recomendaciones' => [],
            ]);
        });

        $response = $this->actingAs($user)->postJson(route('api.comparisons.store'), [
            'cv_id' => $cv->id,
            'job_offer_id' => $jobOffer->id,
        ]);

        $response->assertCreated();
        $response->assertJsonPath('comparison.compatibility_score', 100);
    }

    public function test_comparison_requires_existing_cv_and_job_offer(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.comparisons.store'), [
            'cv_id' => 999,
            'job_offer_id' => 999,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['cv_id', 'job_offer_id']);
    }
}
