<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Work;
use App\CustomerService;
use Faker\Generator as Faker;

$factory->define(Work::class, function (Faker $faker) {
    return [
      // 'customer_service_id' => CustomerService::inRandomOrder()->first()->id,
      'status' => $faker->randomElement(['processing', 'on hold', 'pending', 'completed']),
    ];
});
