<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Dani Demo',
            'email' => 'demo@airecruit.test',
            'password' => 'password',
        ]);

        $profile = $user->profile()->create([
            'headline' => 'Desarrollador Full Stack | Laravel & IA',
            'summary' => 'Desarrollador full stack con más de 5 años de experiencia construyendo aplicaciones web escalables. Especializado en Laravel y PHP, con experiencia reciente integrando modelos de IA para automatizar flujos de trabajo. Apasionado por el código limpio y las soluciones pragmáticas.',
            'phone' => '+34 611 222 333',
            'location' => 'Madrid, España',
            'linkedin_url' => 'https://linkedin.com/in/dani-demo',
            'portfolio_url' => 'https://danidemo.dev',
        ]);

        $profile->experiences()->createMany([
            [
                'company' => 'TechNova Solutions',
                'position' => 'Desarrollador Full Stack Senior',
                'description' => 'Liderazgo técnico de un equipo de 4 desarrolladores en la construcción de una plataforma SaaS de gestión de proyectos con Laravel. Diseño de la arquitectura de la API REST y optimización de queries que redujeron los tiempos de carga en un 40%.',
                'start_date' => '2022-03-01',
                'end_date' => null,
                'is_current' => true,
            ],
            [
                'company' => 'Innovatech Digital',
                'position' => 'Desarrollador Backend',
                'description' => 'Desarrollo y mantenimiento de módulos de facturación y pagos para un ERP interno. Integración con pasarelas de pago y migración de una parte del sistema legacy a Laravel.',
                'start_date' => '2019-06-01',
                'end_date' => '2022-02-28',
                'is_current' => false,
            ],
            [
                'company' => 'StartUp Labs',
                'position' => 'Desarrollador Junior',
                'description' => 'Desarrollo de funcionalidades para una aplicación web de reservas usando PHP y jQuery. Participación en tareas de testing manual y soporte a producción.',
                'start_date' => '2018-01-15',
                'end_date' => '2019-05-31',
                'is_current' => false,
            ],
        ]);

        $profile->educations()->createMany([
            [
                'institution' => 'Universidad Politécnica de Madrid',
                'degree' => 'Grado en Ingeniería Informática',
                'field_of_study' => 'Ingeniería del Software',
                'start_date' => '2013-09-01',
                'end_date' => '2017-06-30',
            ],
            [
                'institution' => 'Máster de Desarrollo con IA',
                'degree' => 'Máster',
                'field_of_study' => 'Inteligencia Artificial Aplicada',
                'start_date' => '2025-10-01',
                'end_date' => null,
            ],
        ]);

        $profile->skills()->createMany([
            ['name' => 'PHP', 'level' => 'Experto'],
            ['name' => 'Laravel', 'level' => 'Experto'],
            ['name' => 'JavaScript', 'level' => 'Avanzado'],
            ['name' => 'SQL', 'level' => 'Avanzado'],
            ['name' => 'Docker', 'level' => 'Intermedio'],
        ]);

        $profile->languages()->createMany([
            ['name' => 'Español', 'level' => 'Nativo'],
            ['name' => 'Inglés', 'level' => 'C1'],
        ]);

        $profile->certifications()->create([
            'name' => 'Laravel Certified Developer',
            'issuer' => 'Laravel LLC',
            'issue_date' => '2023-05-10',
            'credential_url' => 'https://certificates.laravel.com/dani-demo',
        ]);
    }
}
