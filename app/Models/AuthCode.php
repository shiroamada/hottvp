<?php

namespace App\Models;

use App\Repository\Admin\AdminUserRepository;
use Illuminate\Database\Eloquent\Model;

class AuthCode extends Model
{
    protected $guarded = [];

    
    
    public function assort()
    {
        return $this->belongsTo(Assort::class, 'assort_id');
    }

    public function user()
    {
        return $this->belongsTo(AdminUserRepository::class, 'user_id');
    }
}