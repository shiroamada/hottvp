<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotcoinTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'type',
        'amount',
        'description',
        'related_activation_code_id',
        'related_agent_id',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    /**
     * Get the agent associated with this transaction.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the activation code related to this transaction (if any).
     */
    public function relatedActivationCode()
    {
        return $this->belongsTo(ActivationCode::class, 'related_activation_code_id');
    }

    /**
     * Get the other agent related to this transaction (if any, e.g., downline for profit).
     */
    public function relatedAgent()
    {
        return $this->belongsTo(User::class, 'related_agent_id');
    }
}
