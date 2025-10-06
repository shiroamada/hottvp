<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class PasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'old_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! Hash::check($value, auth()->guard('admin')->user()->password)) {
                        $fail(trans('adminUser.old_password_fail'));
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-zA-Z])(?=.*[0-9])[\w\x21-\x7e]{6,18}$/',
            ],
            'password_confirmation' => 'required',
        ];
    }
}
