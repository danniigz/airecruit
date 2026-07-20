<?php

namespace App\Services;

use App\Exceptions\OpenAIException;
use App\Models\JobOffer;
use App\Models\User;

class CoverLetterService
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
     * Genera una carta de presentación personalizada combinando el perfil
     * profesional del usuario con la descripción de una oferta de empleo.
     *
     * @throws OpenAIException
     */
    public function generate(User $user, JobOffer $jobOffer): string
    {
        $prompt = $this->buildPrompt($user, $jobOffer);

        return trim($this->openAIService->ask($prompt, $this->systemPrompt()));
    }

    private function buildPrompt(User $user, JobOffer $jobOffer): string
    {
        $description = mb_substr($jobOffer->description, 0, self::MAX_DESCRIPTION_LENGTH);
        $candidateContext = $this->candidateContext($user);

        return <<<PROMPT
            Escribe una carta de presentación para el siguiente candidato, dirigida a la oferta de empleo indicada.

            CANDIDATO:
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

    private function candidateContext(User $user): string
    {
        $profile = $user->profile;

        $lines = ["Nombre: {$user->name}"];

        if (! $profile) {
            return implode("\n", $lines);
        }

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

    private function systemPrompt(): string
    {
        return <<<PROMPT
            Eres un experto en redacción de cartas de presentación para procesos de
            selección. Escribe una carta de presentación en español, con tono
            profesional y cercano, personalizada para el candidato y la oferta
            proporcionados. Destaca la experiencia y las habilidades del candidato
            que mejor encajen con la oferta.

            Si en los datos del candidato hay experiencias, empresas, proyectos o
            tecnologías concretas, cita explícitamente 1-2 de ellas (por ejemplo, el
            nombre de una empresa real, un proyecto concreto o una tecnología
            específica mencionada en su experiencia) y conéctalas con un requisito
            de la oferta. Evita frases genéricas y vacías del tipo "ecosistema PHP",
            "entorno técnico" o "diversas tecnologías": ancla siempre el contenido a
            datos reales del perfil cuando existan, en lugar de quedarte en
            generalidades. Si no se proporcionan datos de perfil más allá del
            nombre, no inventes empresas, proyectos ni logros; en ese caso redacta
            una carta profesional centrada en la motivación y el interés por la
            oferta, sin fabricar experiencia inexistente.

            La carta debe tener una extensión de entre 200 y 350 palabras, con
            saludo y despedida incluidos. Responde ÚNICAMENTE con el texto de la
            carta, sin explicaciones adicionales, sin comillas envolventes y sin
            formato markdown.
            PROMPT;
    }
}
