<?php

namespace Database\Factories;

use App\Models\CoverLetter;
use App\Models\Cv;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CoverLetter>
 */
class CoverLetterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'job_offer_id' => JobOffer::factory(),
            'cv_id' => Cv::factory(),
            'content' => $this->faker->paragraphs(4, true),
            'generated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
