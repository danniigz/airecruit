<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
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
            'headline' => $this->faker->jobTitle(),
            'summary' => $this->faker->paragraph(),
            'phone' => $this->faker->phoneNumber(),
            'location' => $this->faker->city(),
            'linkedin_url' => 'https://linkedin.com/in/'.$this->faker->userName(),
            'portfolio_url' => $this->faker->url(),
        ];
    }
}
