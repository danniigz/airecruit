<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ExperienceRequest;
use App\Models\Experience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ExperienceController extends Controller
{
    public function store(ExperienceRequest $request): RedirectResponse
    {
        $profile = $request->user()->profile()->firstOrCreate();

        $profile->experiences()->create($this->normalize($request->validated()));

        return Redirect::route('profile.index')->with('status', 'experience-created');
    }

    public function update(ExperienceRequest $request, Experience $experience): RedirectResponse
    {
        $this->authorizeProfileOwnership($request, $experience);

        $experience->update($this->normalize($request->validated()));

        return Redirect::route('profile.index')->with('status', 'experience-updated');
    }

    public function destroy(Request $request, Experience $experience): RedirectResponse
    {
        $this->authorizeProfileOwnership($request, $experience);

        $experience->delete();

        return Redirect::route('profile.index')->with('status', 'experience-deleted');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function normalize(array $data): array
    {
        if ($data['is_current']) {
            $data['end_date'] = null;
        }

        return $data;
    }
}
