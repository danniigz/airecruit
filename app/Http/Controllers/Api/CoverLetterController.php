<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\OpenAIException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CoverLetter\CoverLetterRequest;
use App\Models\JobOffer;
use App\Services\CoverLetterService;
use Illuminate\Http\JsonResponse;

class CoverLetterController extends Controller
{
    public function store(CoverLetterRequest $request): JsonResponse
    {
        $jobOffer = JobOffer::findOrFail($request->validated('job_offer_id'));

        $this->authorizeOwnership($request, $jobOffer);

        try {
            $content = app(CoverLetterService::class)->generate($request->user(), $jobOffer);
        } catch (OpenAIException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        $coverLetter = $request->user()->coverLetters()->create([
            'job_offer_id' => $jobOffer->id,
            'content' => $content,
            'generated_at' => now(),
        ]);

        return response()->json([
            'cover_letter' => [
                'id' => $coverLetter->id,
                'content' => $coverLetter->content,
                'generated_at' => $coverLetter->generated_at->format('d/m/Y H:i'),
                'job_offer' => [
                    'id' => $jobOffer->id,
                    'title' => $jobOffer->title,
                    'company' => $jobOffer->company,
                ],
            ],
        ], 201);
    }
}
