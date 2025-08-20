<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TryCode extends Model
{
    use HasFactory;

    protected $table = 'try_codes'; // Explicitly set table name to match existing usage

    protected $fillable = [
        'user_id',
        'number',
        'description',
    ];
}