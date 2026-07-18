<?php

namespace App\Http\Requests\Cv;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CvUploadRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cv' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cv.required' => 'Debes seleccionar un archivo PDF.',
            'cv.file' => 'El archivo subido no es válido.',
            'cv.mimes' => 'El CV debe ser un archivo PDF.',
            'cv.max' => 'El PDF no puede superar los 2MB.',
        ];
    }
}
