<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Defined extends Model
{
    use HasFactory;

    protected $table = 'defined_assort_levels';

    protected $fillable = ['user_id', 'assort_id', 'money'];
}
