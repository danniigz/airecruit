<?php

namespace App\Services;

use App\Exceptions\OpenAIException;
use GuzzleHttp\Client as GuzzleClient;
use OpenAI\Client;
use OpenAI\Contracts\ClientContract;
use OpenAI\Exceptions\ErrorException;
use OpenAI\Exceptions\RateLimitException;
use OpenAI\Exceptions\ServerException;
use OpenAI\Exceptions\TransporterException;
use OpenAI\Exceptions\UnserializableResponse;

class OpenAIService
{
    /**
     * Modelo rápido y económico de la familia GPT-5.6. No usar el alias
     * corto "gpt-5.6": apunta a Sol (el flagship, mucho más caro).
     */
    private const MODEL = 'gpt-5.6-luna';

    private readonly ClientContract $client;

    public function __construct(?ClientContract $client = null)
    {
        $this->client = $client ?? $this->makeDefaultClient();
    }

    /**
     * Envía un prompt al modelo y devuelve el texto de la respuesta.
     *
     * @throws OpenAIException
     */
    public function ask(string $prompt, ?string $systemPrompt = null, bool $jsonResponse = false): string
    {
        $messages = [];

        if ($systemPrompt !== null) {
            $messages[] = ['role' => 'system', 'content' => $systemPrompt];
        }

        $messages[] = ['role' => 'user', 'content' => $prompt];

        $parameters = [
            'model' => self::MODEL,
            'messages' => $messages,
        ];

        if ($jsonResponse) {
            $parameters['response_format'] = ['type' => 'json_object'];
        }

        try {
            $response = $this->client->chat()->create($parameters);
        } catch (RateLimitException|ServerException|ErrorException $e) {
            throw new OpenAIException('Error en la API de OpenAI: '.$e->getMessage(), previous: $e);
        } catch (TransporterException $e) {
            throw new OpenAIException('No se pudo conectar con la API de OpenAI (timeout o error de red): '.$e->getMessage(), previous: $e);
        } catch (UnserializableResponse $e) {
            throw new OpenAIException('La respuesta de la API de OpenAI no se pudo interpretar: '.$e->getMessage(), previous: $e);
        }

        $content = $response->choices[0]->message->content ?? null;

        if ($content === null || trim($content) === '') {
            throw new OpenAIException('La API de OpenAI devolvió una respuesta vacía.');
        }

        return $content;
    }

    /**
     * Envía un prompt pidiendo explícitamente una respuesta en formato JSON
     * y la devuelve ya decodificada como array asociativo.
     *
     * @return array<string, mixed>
     *
     * @throws OpenAIException
     */
    public function askForJson(string $prompt, ?string $systemPrompt = null): array
    {
        $content = $this->ask($prompt, $systemPrompt, jsonResponse: true);

        $decoded = json_decode($content, associative: true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            throw new OpenAIException('La respuesta de la API de OpenAI no es un JSON válido.');
        }

        return $decoded;
    }

    private function makeDefaultClient(): Client
    {
        $apiKey = config('services.openai.api_key');

        if (empty($apiKey)) {
            throw new OpenAIException('No se ha configurado OPENAI_API_KEY.');
        }

        $httpClient = new GuzzleClient([
            'timeout' => config('services.openai.timeout', 30),
        ]);

        return \OpenAI::factory()
            ->withApiKey($apiKey)
            ->withHttpClient($httpClient)
            ->make();
    }
}
