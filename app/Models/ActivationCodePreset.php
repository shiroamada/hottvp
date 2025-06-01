<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivationCodePreset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type_identifier',
        'hotcoin_cost',
        'duration_days',
        'description',
        'is_active',
    ];

    /**
     * Get the activation codes associated with this preset.
     */
    public function activationCodes()
    {
        return $this->hasMany(ActivationCode::class);
    }
}
