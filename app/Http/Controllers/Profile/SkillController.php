<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\SkillRequest;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SkillController extends Controller
{
    public function store(SkillRequest $request): RedirectResponse
    {
        $profile = $request->user()->profile()->firstOrCreate();

        $profile->skills()->create($request->validated());

        return Redirect::route('profile.index')->with('status', 'skill-created');
    }

    public function update(SkillRequest $request, Skill $skill): RedirectResponse
    {
        $this->authorizeProfileOwnership($request, $skill);

        $skill->update($request->validated());

        return Redirect::route('profile.index')->with('status', 'skill-updated');
    }

    public function destroy(Request $request, Skill $skill): RedirectResponse
    {
        $this->authorizeProfileOwnership($request, $skill);

        $skill->delete();

        return Redirect::route('profile.index')->with('status', 'skill-deleted');
    }
}
