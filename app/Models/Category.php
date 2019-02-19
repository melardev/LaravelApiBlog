<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $table = 'categories';
    // By default is true, but set it explicitly
    public $timestamps = true;

    protected $fillable = [
        'name', 'description'
    ];

    public function categorizable()
    {
        return $this->morphTo();
    }


    static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->slug = str_slug($model->name);
        });

        self::updating(function ($model) {
            $model->slug = str_slug($model->name);
        });
    }
}