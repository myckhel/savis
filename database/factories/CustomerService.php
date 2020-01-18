<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

// use App\Job;
// use App\Payment;
use App\Customer;
use App\CustomerService;
use App\CustomerServiceMeta;
use App\CustomerServiceProperty;
use App\Service;
use Faker\Generator as Faker;

$factory->define(CustomerService::class, function (Faker $faker) {
  $service = Service::inRandomOrder()->first();
  return [
    // 'customer_id' => Customer::inRandomOrder()->first()->id,
    'service_id' => $service ? $service->id : null,
  ];
});

$factory->afterCreating(CustomerService::class, function($service){
  $service->job()->save(factory(App\Work::class)->make());
  $service->payment()->save(factory(App\Payment::class)->make());
  $service->customer_service_property()->save(factory(CustomerServiceProperty::class)->make());
});
