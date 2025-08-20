<?php

namespace App\Models;

use App\Models\Admin\AdminUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthCode extends Model
{
    use HasFactory;

    protected $table = 'auth_codes';

    protected $fillable = ['id', 'assort_id', 'user_id', 'auth_code', 'remark', 'status', 'expire_at'];


    /**
     * Get the assort that the code belongs to.
     */
    public function assort(): BelongsTo
    {
        return $this->belongsTo(Assort::class);
    }

    /**
     * Get the user who generated the code.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'user_id');
    }
}
