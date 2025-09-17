<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AuthCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'assort_id' => 'required|exists:assorts,id',
            'number' => 'required|integer|min:1|max:100',
            'remark' => 'nullable|string|max:128',
            'mini_money' => 'nullable|numeric',
            'type' => 'nullable|integer',
        ];
    }
}