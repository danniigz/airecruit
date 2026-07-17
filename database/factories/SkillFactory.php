<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
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
            'name' => $this->faker->randomElement(['PHP', 'Laravel', 'JavaScript', 'Python', 'SQL', 'Docker', 'Git']),
            'level' => $this->faker->randomElement(['Básico', 'Intermedio', 'Avanzado', 'Experto']),
        ];
    }
}
