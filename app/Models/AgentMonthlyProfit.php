<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentMonthlyProfit extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'month_year',
        'profit_amount',
    ];

    /**
     * Get the agent associated with this monthly profit record.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
