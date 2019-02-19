<?php



$factory->define(App\Models\Category::class, function (Faker\Generator $faker) {
    $name = $faker->word;
    return [
        'name' => $name,
        'description' => $faker->sentence
    ];
});


