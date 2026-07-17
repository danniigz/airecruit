<?php

namespace Database\Factories;

use App\Models\Comparison;
use App\Models\Cv;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comparison>
 */
class ComparisonFactory extends Factory
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
            'cv_id' => Cv::factory(),
            'job_offer_id' => JobOffer::factory(),
            'compatibility_score' => $this->faker->numberBetween(0, 100),
            'ai_feedback' => [
                'strengths' => $this->faker->sentences(2),
                'gaps' => $this->faker->sentences(2),
            ],
        ];
    }
}
