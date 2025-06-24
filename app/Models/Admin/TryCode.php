<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class TryCode extends Model
{
    protected $fillable = ['id', 'user_id', 'number', 'description'];

    public function users()
    {
        return $this->belongsTo('App\Models\Admin\AdminUser', 'user_id', 'id');
    }
}
