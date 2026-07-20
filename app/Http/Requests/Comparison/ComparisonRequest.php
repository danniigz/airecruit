<?php

namespace App\Http\Requests\Comparison;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ComparisonRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cv_id' => ['required', 'integer', 'exists:cvs,id'],
            'job_offer_id' => ['required', 'integer', 'exists:job_offers,id'],
        ];
    }
}
