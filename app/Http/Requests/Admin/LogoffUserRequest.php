<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class LogoffUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => 'nullable|string|max:255',
            'password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! Hash::check($value, auth()->guard('admin')->user()->password)) {
                        $fail(trans('adminUser.old_password_fail'));
                    }
                },
            ],
        ];
    }
}
