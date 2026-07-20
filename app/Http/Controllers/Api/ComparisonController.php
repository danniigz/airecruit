<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\OpenAIException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comparison\ComparisonRequest;
use App\Models\Cv;
use App\Models\JobOffer;
use App\Services\ComparisonService;
use Illuminate\Http\JsonResponse;

class ComparisonController extends Controller
{
    public function store(ComparisonRequest $request): JsonResponse
    {
        $cv = Cv::findOrFail($request->validated('cv_id'));
        $jobOffer = JobOffer::findOrFail($request->validated('job_offer_id'));

        $this->authorizeOwnership($request, $cv);
        $this->authorizeOwnership($request, $jobOffer);

        try {
            $result = app(ComparisonService::class)->compare($cv, $jobOffer);
        } catch (OpenAIException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        $comparison = $request->user()->comparisons()->create([
            'cv_id' => $cv->id,
            'job_offer_id' => $jobOffer->id,
            'compatibility_score' => $result['puntuacion_compatibilidad'],
            'ai_feedback' => $result,
        ]);

        return response()->json([
            'comparison' => [
                'id' => $comparison->id,
                'compatibility_score' => $comparison->compatibility_score,
                'ai_feedback' => $comparison->ai_feedback,
                'created_at' => $comparison->created_at->format('d/m/Y H:i'),
                'cv' => [
                    'id' => $cv->id,
                    'original_filename' => $cv->original_filename,
                ],
                'job_offer' => [
                    'id' => $jobOffer->id,
                    'title' => $jobOffer->title,
                    'company' => $jobOffer->company,
                ],
            ],
        ], 201);
    }
}
