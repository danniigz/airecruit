<?php

namespace App\Services;

use App\Exceptions\OpenAIException;
use App\Models\Cv;
use App\Models\JobOffer;
use App\Models\Profile;

class ComparisonService
{
    /**
     * Límite de caracteres de la descripción de la oferta que se envían a la
     * IA, para evitar prompts desproporcionadamente largos (y caros).
     */
    private const MAX_DESCRIPTION_LENGTH = 8000;

    public function __construct(
        private readonly OpenAIService $openAIService,
    ) {}

    /**
     * Compara el CV (o, en su defecto, el perfil profesional) del candidato
     * con una oferta de empleo y devuelve la puntuación de compatibilidad
     * generada por IA junto con fortalezas, carencias y recomendaciones.
     *
     * @return array{puntuacion_compatibilidad: int, fortalezas: array<int, string>, carencias: array<int, string>, recomendaciones: array<int, string>}
     *
     * @throws OpenAIException
     */
    public function compare(Cv $cv, JobOffer $jobOffer): array
    {
        $prompt = $this->buildPrompt($this->candidateContext($cv), $jobOffer);

        $result = $this->openAIService->askForJson($prompt, $this->systemPrompt());

        return [
            'puntuacion_compatibilidad' => (int) round(max(0, min(100, (float) ($result['puntuacion_compatibilidad'] ?? 0)))),
            'fortalezas' => array_values((array) ($result['fortalezas'] ?? [])),
            'carencias' => array_values((array) ($result['carencias'] ?? [])),
            'recomendaciones' => array_values((array) ($result['recomendaciones'] ?? [])),
        ];
    }

    private function candidateContext(Cv $cv): string
    {
        if ($cv->ai_analysis) {
            return $this->contextFromAnalysis($cv->ai_analysis);
        }

        return $this->contextFromProfile($cv->user->profile);
    }

    /**
     * @param  array<string, mixed>  $analysis
     */
    private function contextFromAnalysis(array $analysis): string
    {
        $puntosFuertes = implode('; ', (array) data_get($analysis, 'puntos_fuertes', []));
        $areasMejora = implode('; ', (array) data_get($analysis, 'areas_mejora', []));
        $skills = implode(', ', (array) data_get($analysis, 'skills_principales', []));

        return
            'Resumen: '.$this->orNone(data_get($analysis, 'resumen'))."\n".
            'Años de experiencia aproximados: '.$this->orNone(data_get($analysis, 'anos_experiencia_aproximados'))."\n".
            'Puntos fuertes: '.$this->orNone($puntosFuertes)."\n".
            'Áreas de mejora: '.$this->orNone($areasMejora)."\n".
            'Skills principales: '.$this->orNone($skills);
    }

    private function contextFromProfile(?Profile $profile): string
    {
        if (! $profile) {
            return 'No hay datos de perfil ni de análisis de CV disponibles.';
        }

        $lines = [];

        $lines[] = 'Titular: '.$this->orNone($profile->headline);
        $lines[] = 'Resumen: '.$this->orNone($profile->summary);

        $lines[] = 'Experiencia:';
        foreach ($profile->experiences as $experience) {
            $lines[] = "- {$experience->position} en {$experience->company}: ".$this->orNone($experience->description);
        }

        $lines[] = 'Formación:';
        foreach ($profile->educations as $education) {
            $lines[] = "- {$education->degree} ({$this->orNone($education->field_of_study)}) en {$education->institution}";
        }

        $lines[] = 'Skills: '.$this->orNone($profile->skills->pluck('name')->implode(', '));
        $lines[] = 'Idiomas: '.$this->orNone($profile->languages->pluck('name')->implode(', '));
        $lines[] = 'Certificaciones: '.$this->orNone($profile->certifications->pluck('name')->implode(', '));

        return implode("\n", $lines);
    }

    private function orNone(mixed $value): string
    {
        $value = is_string($value) ? trim($value) : $value;

        return $value === null || $value === '' ? 'No especificado' : (string) $value;
    }

    private function buildPrompt(string $candidateContext, JobOffer $jobOffer): string
    {
        $description = mb_substr($jobOffer->description, 0, self::MAX_DESCRIPTION_LENGTH);

        return <<<PROMPT
            Compara el siguiente perfil de candidato con la oferta de empleo y evalúa su compatibilidad.

            PERFIL DEL CANDIDATO:
            \"\"\"
            {$candidateContext}
            \"\"\"

            OFERTA DE EMPLEO:
            Puesto: {$jobOffer->title}
            Empresa: {$jobOffer->company}
            Descripción:
            \"\"\"
            {$description}
            \"\"\"
            PROMPT;
    }

    private function systemPrompt(): string
    {
        return <<<PROMPT
            Eres un experto en reclutamiento técnico. Evalúa la compatibilidad entre el
            perfil de un candidato y una oferta de empleo. Responde ÚNICAMENTE con un
            JSON con exactamente esta estructura:

            {
              "puntuacion_compatibilidad": número entero de 0 a 100 (compatibilidad global),
              "fortalezas": ["fortaleza del candidato que encaja con la oferta 1", ...],
              "carencias": ["carencia o gap detectado respecto a la oferta 1", ...],
              "recomendaciones": ["recomendación concreta y accionable 1", ...]
            }

            Responde en español. No incluyas texto fuera del JSON.
            PROMPT;
    }
}
