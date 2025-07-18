<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LicenseCodeRequest extends FormRequest
{
    public function authorize()
    {
        // Only allow authenticated users (agents) to generate codes
        return auth()->check();
    }

    public function rules()
    {
        return [
            'activation_code_preset_id' => 'required|exists:activation_code_presets,id',
            'quantity' => 'required|integer|min:1|max:100',
            'remarks' => 'nullable|string|max:255',
        ];
    }
} 