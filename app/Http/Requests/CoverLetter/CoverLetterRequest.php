<?php

namespace App\Http\Requests\CoverLetter;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CoverLetterRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'job_offer_id' => ['required', 'integer', 'exists:job_offers,id'],
        ];
    }
}
