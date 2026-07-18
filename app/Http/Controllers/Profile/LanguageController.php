<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    public function store(LanguageRequest $request): RedirectResponse
    {
        $profile = $request->user()->profile()->firstOrCreate();

        $profile->languages()->create($request->validated());

        return Redirect::route('profile.index')->with('status', 'language-created');
    }

    public function update(LanguageRequest $request, Language $language): RedirectResponse
    {
        $this->authorizeProfileOwnership($request, $language);

        $language->update($request->validated());

        return Redirect::route('profile.index')->with('status', 'language-updated');
    }

    public function destroy(Request $request, Language $language): RedirectResponse
    {
        $this->authorizeProfileOwnership($request, $language);

        $language->delete();

        return Redirect::route('profile.index')->with('status', 'language-deleted');
    }
}
