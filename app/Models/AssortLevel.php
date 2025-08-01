<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssortLevel extends Model
{
    use HasFactory;

    protected $table = 'assort_levels';

    protected $fillable = ['user_id', 'assort_id', 'money'];
}
