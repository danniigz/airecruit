<?php

namespace App\Http\Controllers\Comparison;

use App\Http\Controllers\Controller;
use App\Models\Comparison;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    public function index(Request $request): View
    {
        $comparisons = $request->user()->comparisons()
            ->with(['cv', 'jobOffer'])
            ->latest()
            ->get();

        return view('comparisons.index', compact('comparisons'));
    }

    public function show(Request $request, Comparison $comparison): View
    {
        $this->authorizeOwnership($request, $comparison);

        $comparison->load(['cv', 'jobOffer']);

        return view('comparisons.show', compact('comparison'));
    }
}
