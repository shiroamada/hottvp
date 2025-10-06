<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Huobi extends Model
{
    use HasFactory;

    protected $table = 'huobis'; // Explicitly set table name to match existing usage

    protected $fillable = ['id', 'user_id', 'event', 'money', 'status', 'created_at', 'updated_at'];

    // protected $fillable = [
    //     'user_id',
    //     'event',
    //     'money',
    //     'status',
    //     'type',
    //     'is_try',
    //     'number',
    //     'own_id',
    //     'create_id',
    //     'assort_id',
    //     'user_account',
    // ];

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
