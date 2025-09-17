<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assort extends Model
{
    protected $guarded = [];
    protected $table = 'en_assorts';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $locale = app()->getLocale();
        if ($locale == 'zh_CN') {
            $this->setTable('assorts');
        } elseif ($locale == 'ms') {
            $this->setTable('my_assorts');
        } else {
            $this->setTable('en_assorts');
        }
    }
}
