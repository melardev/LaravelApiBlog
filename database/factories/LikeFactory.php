<?php


use App\Models\Article;
use App\Models\Like;
use App\Models\User;

/*
$factory->define(Like::class, function (Faker\Generator $faker) {
    return [
        'likeable_type' => Article::class,
        'likeable_id' => function () {
            return factory(Article::class)->create()->id;
        },
        'author_id' => function () {
            return factory(User::class)->create()->id;
        }
    ];
});*/

$factory->define(Like::class, function (Faker\Generator $faker) {
    return [
        'likeable_type' => Article::class,
        'likeable_id' => Article::inRandomOrder()->first()->id,
        'user_id' => User::inRandomOrder()->first()->id
    ];
    /*return [
        'likeable_type' => Comment::class,
        'likeable_id' => function () {
            return factory(Comment::class)->create()->id;
        },
        'user_id' => function () {
            return factory(User::class)->create()->id;
        }
    ];*/
});