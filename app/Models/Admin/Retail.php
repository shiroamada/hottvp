<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retail extends Model
{
    use HasFactory;

    protected $table = 'defined_retail';

    protected $fillable = ['user_id', 'assort_id', 'money'];
}
