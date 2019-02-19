<?php

namespace App\Models;

use App\Events\TagCreatedOrUpdatedEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    // By default is true, but set it explicitly
    public $timestamps = true;

    protected $fillable = [
        'name', 'description'
    ];

    protected $dispatchesEvents = [
        'saving' => TagCreatedOrUpdatedEvent::class,
        'updating' => TagCreatedOrUpdatedEvent::class
    ];

    public function articles() {
        return $this->morphedByMany(Article::class, 'taggable');
    }

}