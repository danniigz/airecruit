<?php

namespace Database\Factories;

use App\Models\Experience;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-8 years', '-1 year');

        return [
            'profile_id' => Profile::factory(),
            'company' => $this->faker->company(),
            'position' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(),
            'start_date' => $startDate,
            'end_date' => $this->faker->dateTimeBetween($startDate, 'now'),
            'is_current' => false,
        ];
    }
}
