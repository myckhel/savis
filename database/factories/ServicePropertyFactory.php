<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ServiceProperty;
use Faker\Generator as Faker;

$factory->define(ServiceProperty::class, function (Faker $faker) {
  return [
    // 'service_id' => Service::inRandomOrder()->first()->id,
    'name' => $faker->word(5,10),
    'rules' => json_encode(['required' => true, 'min' => 3, 'max' => 80]),
  ];
});
