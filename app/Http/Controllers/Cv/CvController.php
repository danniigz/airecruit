<?php

namespace App\Http\Controllers\Cv;

use App\Http\Controllers\Controller;
use App\Models\Cv;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CvController extends Controller
{
    public function index(Request $request): View
    {
        $cvs = $request->user()->cvs()->latest()->get();

        return view('cvs.index', compact('cvs'));
    }

    public function show(Request $request, Cv $cv): View
    {
        $this->authorizeOwnership($request, $cv);

        $jobOffers = $request->user()->jobOffers()->latest()->get();

        return view('cvs.show', compact('cv', 'jobOffers'));
    }
}
