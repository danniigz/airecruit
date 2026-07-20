<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $cvCount = $user->cvs()->count();
        $comparisonCount = $user->comparisons()->count();
        $coverLetterCount = $user->coverLetters()->count();

        return view('dashboard', compact('cvCount', 'comparisonCount', 'coverLetterCount'));
    }
}
