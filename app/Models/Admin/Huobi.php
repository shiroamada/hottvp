<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Huobi extends Model
{
    use HasFactory;

    protected $table = 'huobis'; // Explicitly set table name to match existing usage

    protected $fillable = [
        'user_id',
        'event',
        'money',
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
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get the activation code related to this transaction (if any).
     */
    public function relatedActivationCode()
    {
        return $this->belongsTo(\App\Models\AuthCode::class, 'related_activation_code_id');
    }

    /**
     * Get the other agent related to this transaction (if any, e.g., downline for profit).
     */
    public function relatedAgent()
    {
        return $this->belongsTo(\App\Models\User::class, 'related_agent_id');
    }
}
