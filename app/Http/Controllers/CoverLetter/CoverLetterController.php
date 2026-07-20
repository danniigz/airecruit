<?php

namespace App\Http\Controllers\CoverLetter;

use App\Http\Controllers\Controller;
use App\Models\CoverLetter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CoverLetterController extends Controller
{
    public function index(Request $request): View
    {
        $coverLetters = $request->user()->coverLetters()
            ->with('jobOffer')
            ->latest()
            ->get();

        return view('cover-letters.index', compact('coverLetters'));
    }

    public function show(Request $request, CoverLetter $coverLetter): View
    {
        $this->authorizeOwnership($request, $coverLetter);

        $coverLetter->load('jobOffer');

        return view('cover-letters.show', compact('coverLetter'));
    }
}
