<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // java EE like role names <3
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_AUTHOR = 'ROLE_AUTHOR';
    public const ROLE_USER = 'ROLE_USER';

    // By default is true, but set it explicitly
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

}