<?php

$factory->define(\App\Models\ExampleItem::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name()
    ];
});