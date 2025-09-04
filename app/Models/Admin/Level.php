<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['id', 'level_name'];

    protected $table = 'en_levels'; // Default table, will be overridden by constructor

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $locale = app()->getLocale();
        if ($locale == 'zh_CN') {
            $this->setTable('levels');
        } elseif ($locale == 'ms') {
            $this->setTable('my_levels');
        } else {
            $this->setTable('en_levels');
        }
    }
}
