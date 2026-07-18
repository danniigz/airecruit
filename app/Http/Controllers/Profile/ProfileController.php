<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's professional profile.
     */
    public function index(Request $request): View
    {
        $profile = $request->user()->profile()->firstOrCreate();

        $profile->load([
            'experiences' => fn ($query) => $query->orderByDesc('start_date'),
            'educations' => fn ($query) => $query->orderByDesc('start_date'),
            'skills' => fn ($query) => $query->orderBy('name'),
            'languages' => fn ($query) => $query->orderBy('name'),
            'certifications' => fn ($query) => $query->orderByDesc('issue_date'),
        ]);

        return view('profile.index', [
            'profile' => $profile,
        ]);
    }

    /**
     * Update the authenticated user's general profile data.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $profile = $request->user()->profile()->firstOrCreate();

        $profile->fill($request->validated());
        $profile->save();

        return Redirect::route('profile.index')->with('status', 'profile-updated');
    }
}
