<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HuobiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:admin_users,id',
            'event' => 'required|string|max:255',
            'money' => 'required|numeric|min:0',
            'status' => 'required|integer|in:0,1',
        ];
    }
}
