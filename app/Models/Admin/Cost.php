<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    protected $table = "costs";
    protected $fillable = ['id', 'user_id', 'level_id', 'mini_amount', 'created_at', 'updated_at'];

    public function levels()
    {
        return $this->belongsTo('App\Models\Admin\Level', 'level_id', 'id');
    }
}
