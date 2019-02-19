<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;

class Article extends Model
{
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'description',
        'publish_on'
    ];


    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }

    // Accessor/Mutators
    // Accessor/Mutator
    public function getTagListAttribute() {

        return $this->tags()->pluck('name')->toArray();
    }

    // Which field is gonna be used to retrieve the Article and bind it as a param in Dependency Injection ( Implicit binding )
    public function getRouteKeyName(): string {
        return 'slug';
    }
    /*
     *
     * Get the route key for the model.

    public function getRouteKeyName(): string {
        /*
         *  if (request()->expectsJson()) {
            return 'id';
        }
         */
    /*  return 'slug';
  }
   */
    /**
     * Scope a query to search posts
     */
    public function scopeSearch(Builder $query, ?string $search) {
        if ($search) {
            return $query->where('title', 'LIKE', "%{$search}%");
        }
    }

    public function scopeSearchByColumn(Builder $query, string $column, string $search) {
        return $query->where($column, '=', "%{$search}%");
    }

    /**
     * Scope a query to order posts by latest posted
     */
    public function scopeLatest(Builder $query): Builder {
        return $query->orderBy('created_at', 'desc');
    }


    public function comments(): MorphMany {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function tags() {
        // by default the table name is article_tag
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function likes() {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function categories() {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function getFeed(User $user) {
        return Article::whereIn('user_id', $user->following()->pluck('id'))->get();
    }

    /*
        public function hasImage(): bool {
            return filled($this->image_file_name);
        }
        */
}