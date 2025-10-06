<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\AdminResetPassword;

class AdminUser extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPassword($token));
    }


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
