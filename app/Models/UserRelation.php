<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRelation extends Model
{
    protected $table = 'user_relations';
    protected $fillable = ['following_id', 'follower_id'];

    public function following(): BelongsTo {
        return $this->belongsTo(User::class, 'following_id');
    }

    public function follower(): BelongsTo {
        return $this->belongsTo(User::class, 'follower_id');
    }
}