<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin\Level;
use App\Models\Assort;

class EquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $levelTable = (new Level())->getTable();
        $assortTable = (new Assort())->getTable();

        return [
            'user_id' => 'required|exists:admin_users,id',
            'assort_id' => 'required|exists:' . $assortTable . ',id',
            'level_id' => 'required|exists:' . $levelTable . ',id',
            'money' => 'required|numeric|min:0',
        ];
    }
}
