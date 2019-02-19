<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{

    // By default is true, but set it explicitly
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'article_id',
        'content',
    ];


    public function article(): BelongsTo {
        return $this->belongsTo(Article::class);
    }

    public function scopeLatest(Builder $query): Builder {
        return $query->orderBy('created_at', 'desc');
    }


    /**
     * Scope a query to only include comments posted last week.
     */
    public function scopeLastWeek(Builder $query): Builder {
        return $query->whereBetween('created_at', [carbon('1 week ago'), now()])
                     ->latest();
    }

    /**
     * Return the comment's author
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    //   For a more reusable code, consider using traits and polymorphic relationships, as I did.

    // Retrieves any commentable such as Article or Comment
    public function commentable(): MorphTo {
        return $this->morphTo();
    }

    public function comments(): MorphMany {
        return $this->morphMany(Comment::class, 'commentable');
    }

}