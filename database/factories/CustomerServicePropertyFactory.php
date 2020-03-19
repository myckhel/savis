<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerService;
use App\CustomerProperty;
use App\CustomerServiceProperty;
use App\CustomerServiceServiceProperty;
use Faker\Generator as Faker;

$factory->define(CustomerServiceProperty::class, function (Faker $faker) {

    return [
      // 'customer_service_id' => CustomerService::inRandomOrder()->first()->id,
      // 'customer_property_id' => factory(CustomerProperty::class)->make([]),
      // 'properties' => function($s){
      //   $id = $s['customer_service_id'];
      //   CustomerService::find($id)->
      //   return json_encode(['required' => true, 'min' => 3, 'max' => 80]);
      // },
    ];
});

// create customer_service_service_properties
// $factory->afterCreating(CustomerServiceProperty::class, function($customer){
  // CustomerServiceServiceProperty
// });
