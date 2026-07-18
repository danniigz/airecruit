<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\CertificationRequest;
use App\Models\Certification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CertificationController extends Controller
{
    public function store(CertificationRequest $request): RedirectResponse
    {
        $profile = $request->user()->profile()->firstOrCreate();

        $profile->certifications()->create($request->validated());

        return Redirect::route('profile.index')->with('status', 'certification-created');
    }

    public function update(CertificationRequest $request, Certification $certification): RedirectResponse
    {
        $this->authorizeProfileOwnership($request, $certification);

        $certification->update($request->validated());

        return Redirect::route('profile.index')->with('status', 'certification-updated');
    }

    public function destroy(Request $request, Certification $certification): RedirectResponse
    {
        $this->authorizeProfileOwnership($request, $certification);

        $certification->delete();

        return Redirect::route('profile.index')->with('status', 'certification-deleted');
    }
}
