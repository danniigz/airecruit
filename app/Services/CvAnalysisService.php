<?php

namespace App\Services;

use App\Exceptions\OpenAIException;
use App\Exceptions\PdfExtractionException;
use App\Models\Cv;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Throwable;

class CvAnalysisService
{
    /**
     * Límite de caracteres de texto de CV que se envían a la IA, para evitar
     * prompts desproporcionadamente largos (y caros) con CVs muy extensos.
     */
    private const MAX_TEXT_LENGTH = 12000;

    public function __construct(
        private readonly OpenAIService $openAIService,
    ) {}

    /**
     * Extrae el texto del PDF del CV y lo analiza con IA.
     *
     * @return array<string, mixed>
     *
     * @throws PdfExtractionException
     * @throws OpenAIException
     */
    public function analyze(Cv $cv): array
    {
        $text = $this->extractText($cv);

        return $this->openAIService->askForJson($this->buildPrompt($text), $this->systemPrompt());
    }

    /**
     * @throws PdfExtractionException
     */
    private function extractText(Cv $cv): string
    {
        $absolutePath = Storage::disk('local')->path($cv->file_path);

        try {
            $text = (new Parser())->parseFile($absolutePath)->getText();
        } catch (Throwable $e) {
            throw new PdfExtractionException('No se ha podido leer el contenido del PDF. Comprueba que el archivo no esté dañado o protegido.', previous: $e);
        }

        $text = trim($text);

        if ($text === '') {
            throw new PdfExtractionException('El PDF no contiene texto legible (puede ser un documento escaneado como imagen).');
        }

        return mb_substr($text, 0, self::MAX_TEXT_LENGTH);
    }

    private function buildPrompt(string $text): string
    {
        return <<<PROMPT
            Analiza el siguiente currículum y devuelve el análisis solicitado.

            CURRÍCULUM:
            \"\"\"
            {$text}
            \"\"\"
            PROMPT;
    }

    private function systemPrompt(): string
    {
        return <<<PROMPT
            Eres un experto en reclutamiento y análisis de currículums. Analiza el
            currículum que te proporciona el usuario y responde ÚNICAMENTE con un
            JSON con exactamente esta estructura:

            {
              "resumen": "resumen breve (2-3 frases) del perfil profesional",
              "puntos_fuertes": ["punto fuerte 1", "punto fuerte 2", ...],
              "areas_mejora": ["área de mejora 1", "área de mejora 2", ...],
              "anos_experiencia_aproximados": número (años totales de experiencia estimados, puede ser decimal),
              "skills_principales": ["skill 1", "skill 2", ...]
            }

            Responde en español. No incluyas texto fuera del JSON.
            PROMPT;
    }
}
