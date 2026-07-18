<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\EducationRequest;
use App\Models\Education;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EducationController extends Controller
{
    public function store(EducationRequest $request): RedirectResponse
    {
        $profile = $request->user()->profile()->firstOrCreate();

        $profile->educations()->create($request->validated());

        return Redirect::route('profile.index')->with('status', 'education-created');
    }

    public function update(EducationRequest $request, Education $education): RedirectResponse
    {
        $this->authorizeProfileOwnership($request, $education);

        $education->update($request->validated());

        return Redirect::route('profile.index')->with('status', 'education-updated');
    }

    public function destroy(Request $request, Education $education): RedirectResponse
    {
        $this->authorizeProfileOwnership($request, $education);

        $education->delete();

        return Redirect::route('profile.index')->with('status', 'education-deleted');
    }
}
