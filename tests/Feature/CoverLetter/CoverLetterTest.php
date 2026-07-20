<?php

namespace Tests\Feature\CoverLetter;

use App\Models\JobOffer;
use App\Models\User;
use App\Services\OpenAIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoverLetterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_generate_a_cover_letter_using_mocked_ai(): void
    {
        $user = User::factory()->create();
        $jobOffer = JobOffer::factory()->for($user)->create();

        $generatedContent = 'Estimado equipo de contratación, escribo para expresar mi interés...';

        $this->mock(OpenAIService::class, function ($mock) use ($generatedContent): void {
            $mock->shouldReceive('ask')->once()->andReturn($generatedContent);
        });

        $response = $this->actingAs($user)->postJson(route('api.cover-letters.store'), [
            'job_offer_id' => $jobOffer->id,
        ]);

        $response->assertCreated();
        $response->assertJsonPath('cover_letter.content', $generatedContent);

        $this->assertDatabaseHas('cover_letters', [
            'user_id' => $user->id,
            'job_offer_id' => $jobOffer->id,
            'content' => $generatedContent,
        ]);
    }

    public function test_cover_letter_requires_an_existing_job_offer(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.cover-letters.store'), [
            'job_offer_id' => 999,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('job_offer_id');
    }
}
