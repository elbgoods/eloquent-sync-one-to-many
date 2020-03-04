<?php

use Elbgoods\SyncOneToMany\Tests\Models\Task;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory as ModelFactory;

/* @var ModelFactory $factory */

$factory->define(Task::class, static function (Faker $faker) {
    return [
        'status' => 'open',
        'priority' => rand(0, 10),
        'name' => $faker->word,
    ];
});
