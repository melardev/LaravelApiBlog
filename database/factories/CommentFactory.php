<?php


use App\Models\Article;

$factory->define(App\Models\Comment::class, function (Faker\Generator $faker) {
    return [
        'commentable_type' => Article::class,
        'commentable_id' => Article::inRandomOrder()->first()->id,
        'content' => $faker->paragraph,
        'user_id' => function () {
            // return factory(User::class)->create()->id;
            return \App\Models\User::inRandomOrder()->get()->first()->id;
        }
    ];
});
