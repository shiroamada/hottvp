<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\Level;

class LevelRequest extends FormRequest
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
        $levelTable = (new Level())->getTable();
        $levelId = $this->route('id');

        return [
            'level_name' => 'required|string|max:128|unique:' . $levelTable . ',level_name,' . $levelId,
            'mini_amount' => 'required|numeric|min:0',
        ];
    }
}
