<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Customer;
use App\ServiceProperty;
use Faker\Generator as Faker;
use App\CustomerServiceProperty;

$factory->define(Customer::class, function (Faker $faker) {
  return [
      'firstname' => $faker->firstname,
      'lastname' => $faker->lastname,
      'email' => $faker->unique()->safeEmail,
      'country_code' => $faker->randomNumber(3),
      'phone' => $faker->unique()->phoneNumber,
      'city' => $faker->city,
      'state' => $faker->state,
      'address' => $faker->streetAddress,
      'country' => $faker->country,
      'lat' => $faker->latitude,
      'lng' => $faker->longitude,
  ];
});

$factory->afterCreating(Customer::class, function($customer, $faker){
  $customer_services = $customer->services()->createMany(factory(App\CustomerService::class, 7)->make()->toArray());
  $customer_services->map(function($customer_service)use($customer, $faker){
    $service = $customer_service->service()->first();
    $service_properties = $service->properties()->get();
    $service_properties_index = 0;

    $customer_service->job()->create(factory(App\Work::class)->make()->toArray());
    $customer_service->payment()->create(factory(App\Payment::class)->make()->toArray());

    if ($service_properties) {
      $customer_service->property()->createMany(factory(CustomerServiceProperty::class, $service_properties->count())->make([
        'customer_property_id' => factory(App\CustomerProperty::class)->create([
            'customer_id' => $customer->id,
            'service_property_id' => $service->first()->id,
            'value' => $faker->word(5, 10),
          ])->toArray()['id'],
          'service_property_id' => $service_properties[$service_properties_index++]->id
        ])->toArray());
    }
  });
});
