<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $profile->certifications()->createMany([
            [
                'name' => 'Laravel Certified Developer',
                'issuer' => 'Laravel LLC',
                'issue_date' => '2023-05-10',
                'credential_url' => 'https://certificates.laravel.com/dani-demo',
            ],
            [
                'name' => 'AWS Certified Cloud Practitioner',
                'issuer' => 'Amazon Web Services',
                'issue_date' => '2024-11-02',
                'credential_url' => 'https://aws.amazon.com/verification/dani-demo',
            ],
        ]);

        // --- CVs con análisis de IA ya generado ---------------------------

        $cvPrincipal = $user->cvs()->create([
            'file_path' => $this->storeDummyPdf($user->id, 'CV Dani Demo - Actualizado 2026'),
            'original_filename' => 'cv_dani_demo_2026.pdf',
            'ai_analysis' => [
                'resumen' => 'Desarrollador full stack con más de 5 años de experiencia en Laravel y PHP, con especialización reciente en integración de modelos de IA en flujos de trabajo empresariales. Perfil orientado a backend con buena capacidad de liderazgo técnico.',
                'puntos_fuertes' => [
                    'Sólida experiencia con Laravel y arquitecturas de API REST',
                    'Experiencia liderando equipos técnicos pequeños',
                    'Capacidad demostrada de optimización de rendimiento (reducción de tiempos de carga en un 40%)',
                    'Experiencia reciente integrando IA (OpenAI) en aplicaciones reales',
                ],
                'areas_mejora' => [
                    'Poca experiencia documentada con frameworks frontend modernos (React/Vue)',
                    'Sin certificaciones de arquitectura cloud avanzadas (solo nivel practitioner)',
                    'No se menciona experiencia con Kubernetes u orquestación a gran escala',
                ],
                'anos_experiencia_aproximados' => 6,
                'skills_principales' => ['PHP', 'Laravel', 'JavaScript', 'SQL', 'Docker'],
            ],
            'analyzed_at' => now()->subDays(6),
        ]);

        $cvAnterior = $user->cvs()->create([
            'file_path' => $this->storeDummyPdf($user->id, 'CV Dani Demo - Version 2024'),
            'original_filename' => 'cv_dani_demo_2024.pdf',
            'ai_analysis' => [
                'resumen' => 'Desarrollador backend con experiencia consolidada en PHP y Laravel, enfocado en sistemas de facturación y APIs internas. Perfil técnico sólido pero con menor exposición a proyectos de IA en el momento de este CV.',
                'puntos_fuertes' => [
                    'Buen dominio de PHP y bases de datos relacionales',
                    'Experiencia con integraciones de pasarelas de pago',
                    'Trayectoria estable con progresión de junior a posiciones de mayor responsabilidad',
                ],
                'areas_mejora' => [
                    'Sin experiencia documentada en proyectos de inteligencia artificial',
                    'Exposición limitada a metodologías ágiles a nivel de liderazgo',
                    'Sin certificaciones técnicas en el momento de este CV',
                ],
                'anos_experiencia_aproximados' => 4,
                'skills_principales' => ['PHP', 'Laravel', 'MySQL', 'jQuery'],
            ],
            'analyzed_at' => now()->subMonths(8),
        ]);

        // --- Ofertas de empleo ----------------------------------------------

        $ofertaBackendSenior = $user->jobOffers()->create([
            'title' => 'Backend Developer Senior (Laravel)',
            'company' => 'CloudWorks Tech',
            'description' => <<<'DESC'
                En CloudWorks Tech buscamos un/a Backend Developer Senior para unirse a nuestro equipo de plataforma, encargado de construir y mantener los servicios core de nuestro SaaS B2B.

                Responsabilidades:
                - Diseñar y mantener APIs REST con Laravel para múltiples equipos consumidores.
                - Liderar decisiones de arquitectura backend junto al equipo de plataforma.
                - Optimizar el rendimiento de consultas y procesos en producción.
                - Mentorizar a desarrolladores junior del equipo.

                Requisitos:
                - Más de 4 años de experiencia con PHP y Laravel en entornos de producción.
                - Experiencia diseñando APIs REST escalables.
                - Conocimientos sólidos de SQL y optimización de bases de datos.
                - Valorable experiencia con Docker y despliegues en la nube.
                - Se valora experiencia previa liderando o mentorizando equipos técnicos.

                Ofrecemos horario flexible, modalidad híbrida y presupuesto anual de formación.
                DESC,
            'url' => 'https://cloudworks-tech.example.com/careers/backend-senior',
        ]);

        $ofertaFullstack = $user->jobOffers()->create([
            'title' => 'Full Stack Developer',
            'company' => 'Bright Solutions',
            'description' => <<<'DESC'
                Bright Solutions, consultora digital especializada en e-commerce, busca incorporar un/a Full Stack Developer para su equipo de proyectos cliente.

                Responsabilidades:
                - Desarrollo de funcionalidades end-to-end en aplicaciones Laravel + Vue.js.
                - Colaboración directa con diseño UX/UI para implementar interfaces responsivas.
                - Integración con APIs de terceros (pasarelas de pago, logística, CRMs).
                - Participación en reuniones de planificación ágil (Scrum) con el cliente.

                Requisitos:
                - Experiencia con Laravel en el backend.
                - Experiencia práctica con Vue.js o React en el frontend (imprescindible).
                - Conocimientos de metodologías ágiles.
                - Inglés nivel intermedio para comunicación con clientes internacionales.
                - Se valora experiencia previa en proyectos de e-commerce.

                Se ofrece contrato indefinido, teletrabajo 3 días/semana y proyectos internacionales.
                DESC,
            'url' => 'https://brightsolutions.example.com/jobs/fullstack-dev',
        ]);

        $ofertaDataScience = $user->jobOffers()->create([
            'title' => 'Data Scientist / ML Engineer',
            'company' => 'Quantum Analytics',
            'description' => <<<'DESC'
                Quantum Analytics busca un/a Data Scientist / ML Engineer para reforzar su equipo de modelos predictivos aplicados al sector financiero.

                Responsabilidades:
                - Diseño, entrenamiento y evaluación de modelos de machine learning.
                - Construcción de pipelines de datos en Python (pandas, scikit-learn, PyTorch).
                - Despliegue de modelos en producción y monitorización de su rendimiento.
                - Comunicación de resultados a stakeholders no técnicos.

                Requisitos:
                - Título universitario en Matemáticas, Estadística, Ciencia de Datos o similar.
                - Experiencia sólida con Python y librerías de machine learning.
                - Conocimientos de estadística avanzada y modelado predictivo.
                - Experiencia con plataformas cloud de ML (SageMaker, Vertex AI) valorable.
                - Se valora experiencia previa en el sector financiero o fintech.

                Ofrecemos salario competitivo, formación continua y acceso a clústeres de cómputo propios.
                DESC,
            'url' => 'https://quantum-analytics.example.com/careers/ml-engineer',
        ]);

        // --- Comparaciones de compatibilidad ya generadas -------------------

        $user->comparisons()->create([
            'cv_id' => $cvPrincipal->id,
            'job_offer_id' => $ofertaBackendSenior->id,
            'compatibility_score' => 82,
            'ai_feedback' => [
                'puntuacion_compatibilidad' => 82,
                'fortalezas' => [
                    'Más de 5 años de experiencia con PHP y Laravel en entornos de producción, encaja directamente con el requisito principal',
                    'Experiencia liderando un equipo de 4 desarrolladores, alineada con la mentoría de junior que pide la oferta',
                    'Track record demostrable de optimización de rendimiento (reducción del 40% en tiempos de carga)',
                    'Conocimientos de Docker que cubren el "valorable" de despliegues en la nube',
                ],
                'carencias' => [
                    'No se menciona experiencia específica desplegando en proveedores cloud concretos (AWS, GCP, Azure) más allá de la certificación practitioner',
                    'Sin evidencia de haber diseñado APIs consumidas por múltiples equipos a gran escala',
                ],
                'recomendaciones' => [
                    'Destacar en la entrevista casos concretos de mentoría técnica a perfiles junior',
                    'Preparar ejemplos cuantificados de optimización de queries y arquitectura de APIs',
                    'Reforzar conocimientos prácticos de despliegue cloud más allá del nivel certificado actual',
                ],
            ],
        ]);

        $user->comparisons()->create([
            'cv_id' => $cvPrincipal->id,
            'job_offer_id' => $ofertaFullstack->id,
            'compatibility_score' => 54,
            'ai_feedback' => [
                'puntuacion_compatibilidad' => 54,
                'fortalezas' => [
                    'Base sólida en Laravel que cubre el requisito de backend de la oferta',
                    'Experiencia con JavaScript que aporta una base para trabajar con frameworks frontend',
                    'Buen nivel de inglés (C1), suficiente para comunicación con clientes internacionales',
                ],
                'carencias' => [
                    'No se documenta experiencia práctica con Vue.js ni React, requisito imprescindible de la oferta',
                    'Sin experiencia visible en proyectos de e-commerce',
                    'No se menciona trabajo directo con equipos de diseño UX/UI',
                ],
                'recomendaciones' => [
                    'Realizar un proyecto personal o formación específica en Vue.js para cubrir el gap principal',
                    'Destacar cualquier experiencia previa integrando APIs de terceros, aunque no sea de e-commerce',
                    'Prepararse para justificar en la entrevista la disposición a reforzar el frontend a corto plazo',
                ],
            ],
        ]);

        $user->comparisons()->create([
            'cv_id' => $cvPrincipal->id,
            'job_offer_id' => $ofertaDataScience->id,
            'compatibility_score' => 28,
            'ai_feedback' => [
                'puntuacion_compatibilidad' => 28,
                'fortalezas' => [
                    'Formación académica en Ingeniería Informática y máster en curso en IA Aplicada muestra interés en el ámbito',
                    'Experiencia general en desarrollo de software que facilita la adaptación a nuevos entornos técnicos',
                ],
                'carencias' => [
                    'Sin experiencia profesional demostrable en Python, pandas, scikit-learn o PyTorch',
                    'No se menciona experiencia con modelado estadístico ni machine learning aplicado',
                    'Perfil orientado a backend web (PHP/Laravel), muy alejado del stack de ciencia de datos requerido',
                    'Sin experiencia en el sector financiero o fintech',
                ],
                'recomendaciones' => [
                    'Completar el máster en IA Aplicada con proyectos prácticos en Python y librerías de ML',
                    'Construir un portfolio de proyectos de machine learning antes de aplicar a roles de Data Scientist',
                    'Considerar roles híbridos (MLOps, ingeniería de plataformas de IA) como puente entre el perfil actual y ciencia de datos pura',
                ],
            ],
        ]);

        // --- Cartas de presentación ya generadas -----------------------------

        $user->coverLetters()->create([
            'job_offer_id' => $ofertaBackendSenior->id,
            'cv_id' => $cvPrincipal->id,
            'content' => <<<'LETTER'
                Estimado equipo de selección de CloudWorks Tech:

                Me dirijo a ustedes con motivo de la vacante de Backend Developer Senior (Laravel) publicada recientemente, un puesto que encaja de forma natural con mi trayectoria profesional y mis intereses técnicos.

                Actualmente trabajo como Desarrollador Full Stack Senior en TechNova Solutions, donde lidero un equipo de cuatro desarrolladores en la construcción de una plataforma SaaS de gestión de proyectos con Laravel. Durante este tiempo he diseñado la arquitectura de la API REST del producto y he optimizado consultas críticas, logrando reducir los tiempos de carga en un 40%. Esta experiencia me ha dado una visión completa de cómo escalar servicios backend manteniendo la calidad del código y el rendimiento en producción, algo que entiendo es central en el rol que ofrecen.

                Antes de TechNova, en Innovatech Digital, desarrollé y mantuve módulos de facturación y pagos para un ERP interno, incluyendo integraciones con pasarelas de pago y la migración de partes de un sistema legacy a Laravel. Ese trabajo me enseñó a moverme con soltura en bases de código complejas y a tomar decisiones de arquitectura con impacto real en el negocio.

                Me atrae especialmente la posibilidad de mentorizar a desarrolladores junior dentro de su equipo de plataforma, ya que es una faceta que ya desempeño en mi puesto actual y que disfruto especialmente. Además, estoy completando un Máster de Desarrollo con IA que me ha permitido empezar a integrar modelos de OpenAI en flujos de trabajo reales, una perspectiva que creo puede aportar valor añadido a un equipo orientado a la innovación como el suyo.

                Quedo a su disposición para ampliar cualquier detalle de mi candidatura en una entrevista. Agradezco de antemano su tiempo y consideración.

                Un cordial saludo,
                Dani Demo
                LETTER,
            'generated_at' => now()->subDays(5),
        ]);

        $user->coverLetters()->create([
            'job_offer_id' => $ofertaFullstack->id,
            'cv_id' => $cvPrincipal->id,
            'content' => <<<'LETTER'
                Estimado equipo de Bright Solutions:

                He visto con interés su oferta de Full Stack Developer y me gustaría presentarles mi candidatura para el puesto.

                Actualmente soy Desarrollador Full Stack Senior en TechNova Solutions, donde trabajo a diario con Laravel construyendo y manteniendo una plataforma SaaS de gestión de proyectos, además de liderar a un equipo de cuatro desarrolladores en el diseño de su arquitectura de API REST. Esta experiencia me ha dado una base sólida en el desarrollo backend con Laravel, que entiendo es una pieza clave del stack que utilizan en sus proyectos de e-commerce.

                Aunque mi experiencia frontend más reciente se ha centrado en JavaScript vanilla más que en frameworks como Vue.js, tengo una alta capacidad de adaptación técnica: en mi trayectoria he pasado de trabajar con jQuery en StartUp Labs a liderar arquitecturas backend modernas en pocos años, y estoy dispuesto a reforzar activamente mis conocimientos de Vue.js para aportar valor completo en un rol full stack como el que ofrecen.

                Mi nivel de inglés C1 me permite comunicarme con fluidez con equipos y clientes internacionales, algo que veo mencionado como parte del día a día en Bright Solutions. Asimismo, mi experiencia integrando pasarelas de pago durante mi etapa en Innovatech Digital me da una base práctica relevante para proyectos de e-commerce.

                Me encantaría poder conversar con ustedes sobre cómo puedo aportar a sus proyectos de cliente. Quedo a su disposición para una entrevista.

                Reciban un cordial saludo,
                Dani Demo
                LETTER,
            'generated_at' => now()->subDays(2),
        ]);
    }

    /**
     * Genera un PDF mínimo válido con un título de ejemplo y lo guarda en el
     * disco 'local', devolviendo la ruta relativa generada. Evita depender de
     * PDFs reales o de llamadas a la API de IA al sembrar la base de datos.
     */
    private function storeDummyPdf(int $userId, string $title): string
    {
        $text = 'BT /F1 16 Tf 50 750 Td ('.$this->escapePdfText($title).") Tj ET\n".
            'BT /F1 10 Tf 50 720 Td (Documento de ejemplo generado para la demo de AIRecruit.) Tj ET';

        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            2 => '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            3 => '<< /Type /Page /Parent 2 0 R /Resources << /Font << /F1 4 0 R >> >> /MediaBox [0 0 612 792] /Contents 5 0 R >>',
            4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            5 => "<< /Length ".strlen($text)." >>\nstream\n{$text}\nendstream",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $number => $body) {
            $offsets[$number] = strlen($pdf);
            $pdf .= "{$number} 0 obj\n{$body}\nendobj\n";
        }

        $xrefStart = strlen($pdf);
        $count = count($objects) + 1;

        $pdf .= "xref\n0 {$count}\n0000000000 65535 f \n";
        foreach ($offsets as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        $pdf .= "trailer\n<< /Size {$count} /Root 1 0 R >>\nstartxref\n{$xrefStart}\n%%EOF";

        $path = "cvs/{$userId}/".Str::uuid().'.pdf';
        Storage::disk('local')->put($path, $pdf);

        return $path;
    }

    private function escapePdfText(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
