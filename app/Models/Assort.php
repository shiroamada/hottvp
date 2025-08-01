<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assort extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'assort_name', 'duration'];

    protected $table = 'assorts'; // Assuming 'assorts' is the base table name

    // If multilingual support is needed for this table, it should be handled
    // using Laravel's localization features or separate models/tables.
    // The dynamic table naming based on session language from the old project
    // is not a standard Laravel 12 practice.
    public function levels()
    {
        return $this->hasMany(AssortLevel::class);
    }
}
