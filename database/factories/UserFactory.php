<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'lat' => $faker->latitude,
        'lng' => $faker->longitude,
        'activation_token' => Str::random(10),
        'remember_token' => Str::random(10),
    ];
});

$factory->afterCreating(User::class, function($user){
  try {
    makeService($user);
  } catch (\Exception $e) {
    makeService($user);
  }
});

function makeService($user){
  $services = $user->services()->createMany(factory(App\Service::class, 5)->make()->toArray());
  $services->map(function ($service) {
    try {
      $service->properties()->createMany(factory(App\ServiceProperty::class, 3)->make()->toArray());
      // $service->update(['service_id' => $service->service()->inRandomOrder()->first()->id]);
    } catch (\Exception $e) {
      print($e->getMessage());
    }
  });
}

// $factory->afterMaking(User::class, function($user){
//   $user->services()->save(factory(App\Service::class)->make());
// });
