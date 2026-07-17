<?php

namespace Database\Factories;

use App\Models\Education;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Education>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-10 years', '-2 years');

        return [
            'profile_id' => Profile::factory(),
            'institution' => $this->faker->company().' University',
            'degree' => $this->faker->randomElement(['Grado', 'Máster', 'Doctorado', 'Formación Profesional']),
            'field_of_study' => $this->faker->randomElement(['Informática', 'Ingeniería', 'Administración', 'Marketing', 'Diseño']),
            'start_date' => $startDate,
            'end_date' => $this->faker->dateTimeBetween($startDate, 'now'),
        ];
    }
}
