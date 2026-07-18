<?php

namespace App\Http\Requests\JobOffer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class JobOfferRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:20000'],
            'url' => ['nullable', 'url', 'max:2048'],
        ];
    }
}
