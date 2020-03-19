<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Service;
use App\ServiceMeta;
use App\ServiceProperty;
use Faker\Generator as Faker;

$factory->define(Service::class, function (Faker $faker) {
  return [
    // user_id
    'name' => $faker->word(10),
    'price' => $faker->randomNumber(4),
    'charge' => $faker->randomElement(['+', '*']).$faker->randomNumber(3),
  ];
});

// $factory->afterMaking(Service::class, function($service, $c){
  // print_r($service);
  // print_r($c);
// });

// $factory->state(Service::class, function($service){
// });

$factory->afterCreating(Service::class, function($service){
  print($service->id);
  try {
    $service->properties()->createMany(factory(ServiceProperty::class, 3)->make()->toArray());
    $service->update(['service_id' => $service->service()->inRandomOrder()->first()->id]);
  } catch (\Exception $e) {}
  // $user->services()->createMany(factory(App\Service::class, 30)->make()->toArray());
});
