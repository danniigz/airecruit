<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\OpenAIException;
use App\Exceptions\PdfExtractionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cv\CvUploadRequest;
use App\Models\Cv;
use App\Services\CvAnalysisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CvController extends Controller
{
    public function store(CvUploadRequest $request): JsonResponse
    {
        $file = $request->file('cv');
        $user = $request->user();

        $path = $file->storeAs(
            "cvs/{$user->id}",
            Str::uuid().'.pdf',
            'local',
        );

        $cv = $user->cvs()->create([
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
        ]);

        return response()->json([
            'cv' => [
                'id' => $cv->id,
                'original_filename' => $cv->original_filename,
                'created_at' => $cv->created_at->format('d/m/Y H:i'),
            ],
        ], 201);
    }

    public function analyze(Request $request, Cv $cv): JsonResponse
    {
        $this->authorizeOwnership($request, $cv);

        try {
            $analysis = app(CvAnalysisService::class)->analyze($cv);
        } catch (PdfExtractionException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (OpenAIException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        $cv->update([
            'ai_analysis' => $analysis,
            'analyzed_at' => now(),
        ]);

        return response()->json([
            'analysis' => $analysis,
            'analyzed_at' => $cv->analyzed_at->format('d/m/Y H:i'),
        ]);
    }
}
