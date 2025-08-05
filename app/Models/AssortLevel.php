<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssortLevel extends Model
{
    use HasFactory;

    protected $table = 'assort_levels';

    protected $fillable = ['id', 'level_id', 'assort_id', 'money', 'created_at', 'updated_at'];
    
    public function levels()
    {
        return $this->belongsTo('App\Model\Admin\Level', 'level_id', 'id');
    }

    public function assorts()
    {
        return $this->belongsTo('App\Model\Admin\Assort', 'assort_id', 'id');
    }
}
