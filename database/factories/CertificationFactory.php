<?php

namespace Database\Factories;

use App\Models\Certification;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Certification>
 */
class CertificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'profile_id' => Profile::factory(),
            'name' => $this->faker->randomElement(['AWS Certified Developer', 'Scrum Master', 'Google Analytics', 'PMP', 'Laravel Certified Developer']),
            'issuer' => $this->faker->company(),
            'issue_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'credential_url' => $this->faker->url(),
        ];
    }
}
