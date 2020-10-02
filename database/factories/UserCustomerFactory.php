<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Model;
use App\Models\Customer;
use Faker\Generator as Faker;
use App\Models\UserCustomer;

$factory->define(UserCustomer::class, function (Faker $faker) {
    return [
      // 'user_id' => User::inRandomOrder()->first()->id,
      'customer_id' => Customer::inRandomOrder()->first()->id,
    ];
});
