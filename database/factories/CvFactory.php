<?php

namespace Database\Factories;

use App\Models\Cv;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cv>
 */
class CvFactory extends Factory
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
            'file_path' => 'cvs/'.$this->faker->uuid().'.pdf',
            'original_filename' => $this->faker->word().'_cv.pdf',
            'ai_analysis' => null,
            'analyzed_at' => null,
        ];
    }
}
