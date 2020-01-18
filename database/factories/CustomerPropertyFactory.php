<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerProperty;
use App\ServiceProperty;
use Faker\Generator as Faker;

$factory->define(CustomerProperty::class, function (Faker $faker) {
    return [
      // 'customer_id' => Customer::inRandomOrder()->first()->id,
      // 'service_property_id' => ServiceProperty::inRandomOrder()->first()->id,
      'value' => $faker->word(5,10),
    ];
});
