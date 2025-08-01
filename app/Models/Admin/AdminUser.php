<?php

namespace App\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class AdminUser extends Authenticatable
{
    use HasRoles;

    const STATUS_ENABLE = 1;

    const STATUS_DISABLE = 0;

    protected $guarded = [];

    protected $guard_name = 'admin';

    public static $searchField = [
        'name' => 'Name',
        'status' => [
            'showType' => 'select',
            'searchType' => '=',
            'title' => 'Status',
            'enums' => [
                0 => 'Disable',
                1 => 'Enable',
            ],
        ],
        'created_at' => [
            'showType' => 'datetime',
            'title' => 'Created At',
        ],
    ];

    public function comments()
    {
        return $this->hasMany('App\Models\Admin\Comment', 'user_id');
    }

    public function levels()
    {
        return $this->belongsTo('App\Models\Admin\Level', 'level_id', 'id');
    }
}
