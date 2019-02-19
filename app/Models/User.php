<?php

namespace App\Models;

use App\Events\UserPrePersistEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    public const VISIBILITY_VISIBLE = 0;
    public const VISIBILITY_PRIVATE = 1;
    public const VISIBILITY_UNLISTED = 2;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dispatchesEvents = [
        'saved' => UserPrePersistEvent::class,
        'updated' => UserPrePersistEvent::class
    ];

    public function getRouteKeyName(Request $request = null)
    {
        return 'id';
    }


    /**
     * Scope a query to only include users registered last week.
     */
    public function scopeLastWeek(Builder $query): Builder
    {
        return $query->whereBetween('registered_at', [carbon('1 week ago'), now()])
            ->latest();
    }


    public function articles(): HasMany
    {
        // return $this->hasMany(Article::class)->latest();
        // We don't want the column on the Article's table to be called
        // user_id (the default) but author_id

        return $this->hasMany(Article::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'users_roles')->withTimestamps();
    }

    public function isAuthor(): bool
    {
        return $this->hasRole(Role::ROLE_AUTHOR);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ROLE_ADMIN);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles->where('name', $role)->isNotEmpty();
    }

    public function scopeAuthors(Builder $query_builder): Builder
    {
        return $query_builder->whereHas('roles', function ($query) {
            $query->where('roles.name', Role::ROLE_AUTHOR)
                ->orWhere('roles.name', Role::ROLE_ADMIN);
        });
    }

    // Follow Feature

    /**
     * Follow the given user.
     *
     * @param User $user
     * @return mixed
     */
    public function follow($user_or_id)
    {
        if (is_numeric($user_or_id)) {
            if ($this->following()->where('following_id', $user_or_id)->count() <= 0)
                return $this->following()->attach($user_or_id);
        } else if (is_a($user_or_id, User::class)) {
            if (!$this->isFollowing($user_or_id) && $this->id != $user_or_id->id)
                return $this->following()->attach($user_or_id);

        }

        return null;
    }


    /**
     * Unfollow the given user.
     *
     * @param User $user
     * @return mixed
     */
    public function unFollow($user_or_id)
    {
        if (is_numeric($user_or_id)) {
            if ($this->following()->where('following_id', $user_or_id)->count() > 0)
                return $this->following()->detach($user_or_id);
        } else if (is_a($user_or_id, User::class)) {
            if ($this->isFollowing($user_or_id) && $this->id != $user_or_id->id)
                return $this->following()->detach($user_or_id);

        }
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_relations', 'follower_id', 'following_id')->withTimestamps();
    }

    /**
     * Get all the users that are following this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_relations', 'following_id', 'follower_id')->withTimestamps();
    }

    public function isFollowing(User $user)
    {
        return !!$this->following()->where('following_id', $user->id)->count();
    }

    public function isFollowedBy(User $user)
    {
        return !!$this->followers()->where('follower_id', $user->id)->count();
    }


    public function getAllLikesAttribute()
    {
        return $this->hasMany('Like', 'pid', 'pid')->count();
    }

    public function siteSubscription(): HasOne
    {
        return $this->hasOne(SiteSubscription::class);
    }

    // Useful for seend and recommendations, this finds potential users to follow.
    static function getRandomUserNotFollowedBy(User $user)
    {
        return DB::table('users')
            ->inRandomOrder()
            ->select('id')
            ->whereNotIn('id', $user->following()->pluck('id'))->first();
    }

    static function getPotentialFollowingRecommendationBasedOnFollowingFromUser($user)
    {
        // $user(this is where we are) -> following -> following(this is what we return)
        $to_be_followed = DB::table('user_relations')
            ->select('following_id as id')// select following_id column as id;
            ->whereIn('follower_id', $user->following->pluck('id'))// retrieve what my following are following
            ->where('following_id', '!=', $user->id)// exclude current user($user)
            ->first();
        if ($to_be_followed != null && empty($to_be_followed))
            return $to_be_followed->id;
        return null;
    }

    // Useful for seeding, This finds potential followers get any user that is not following yet the $user parameter
    static function getRandomUserNotFollowingBy(User $user)
    {
        return DB::table('users')
            ->inRandomOrder()
            ->select('id')
            ->whereNotIn('id', $user->followers()->pluck('id'))->first();
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            // if ($model->roles()->count() == 0)
            //    $model->roles()->attach(Role::where('name', Role::ROLE_USER)->first());
        });

        self::updating(function ($model) {
            if ($model->roles()->count() == 0)
                $model->roles()->sync(Role::where('name', Role::ROLE_USER)->first());
        });
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'username' => $this->username
        ];
    }
}
