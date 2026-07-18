<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Abort with a 403 unless the given profile-owned item belongs to the
     * authenticated user's profile.
     *
     * @param  Model&object{profile: BelongsTo}  $item
     */
    protected function authorizeProfileOwnership(Request $request, Model $item): void
    {
        abort_unless($item->profile->user_id === $request->user()->id, 403);
    }
}
