<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'activation_code_preset_id',
        'generated_by_agent_id',
        'status',
        'assigned_to_agent_id',
        'activated_by_user_id',
        'hotcoin_cost_at_generation',
        'duration_days_at_generation',
        'generated_at',
        'assigned_at',
        'activated_at',
        'expires_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'assigned_at' => 'datetime',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the preset for this activation code.
     */
    public function preset()
    {
        return $this->belongsTo(ActivationCodePreset::class, 'activation_code_preset_id');
    }

    /**
     * Get the agent who generated this code.
     */
    public function generatorAgent()
    {
        return $this->belongsTo(User::class, 'generated_by_agent_id');
    }

    /**
     * Get the agent to whom this code was assigned (if any).
     */
    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to_agent_id');
    }

    /**
     * Get the user who activated this code (if any).
     */
    public function activatingUser()
    {
        return $this->belongsTo(User::class, 'activated_by_user_id');
    }

    /**
     * Get the hotcoin transactions related to this activation code.
     */
    public function hotcoinTransactions()
    {
        return $this->hasMany(HotcoinTransaction::class, 'related_activation_code_id');
    }
}
