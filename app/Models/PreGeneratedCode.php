<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreGeneratedCode extends Model
{
    use HasFactory;

    public const TYPES = [
        '30days' => '30 Days',
        '90days' => '90 Days',
        '180days' => '180 Days',
        '365days' => '365 Days',
    ];

    public const VENDORS = [
        'wowtv' => 'WowTV',
        'hottv' => 'HotTV',
    ];

    protected $fillable = [
        'code',
        'type',
        'vendor',
        'remark',
        'assort_level_id',
        'imported_by',
        'imported_at',
        'requested_by',
        'requested_at',
    ];

    public function assortLevel()
    {
        return $this->belongsTo(AssortLevel::class);
    }

    public function importer()
    {
        return $this->belongsTo(Admin\AdminUser::class, 'imported_by');
    }

    public function requester()
    {
        return $this->belongsTo(Admin\AdminUser::class, 'requested_by');
    }
}
