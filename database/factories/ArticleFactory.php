<?php


$factory->define(App\Models\Article::class, function (Faker\Generator $faker) {
    $title = $faker->sentence;

    return [
        'description' => implode($faker->sentences(3), '\n'),
        'title' => $title,
        'body' => implode("<br/>", $faker->paragraphs(5)),
        'slug' => str_slug($title),
        'publish_on' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = '+1 month'),
        'user_id' => function () {
            //return \App\Models\User::inRandomOrder()->get()->take(1)[0]->id;
            //return factory(\App\Models\User::class)->create()->id;
            return \App\Models\User::whereHas('roles', function ($query) {
                return $query->where('roles.name', \App\Models\Role::ROLE_AUTHOR);
            })->inRandomOrder()->first()->id;
        },
        'views' => $faker->numberBetween($min = 0, $max = 13500)
    ];
});