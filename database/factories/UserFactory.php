<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'lat' => $this->faker->latitude,
            'lng' => $this->faker->longitude,
            'activation_token' => Str::random(10),
            'remember_token' => Str::random(10),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (User $user) {
            $user->services()->save(factory(App\Models\Service::class)->make());
        })->afterCreating(function (User $user) {
          try {
            makeService($user);
          } catch (\Exception $e) {
            makeService($user);
          }
        });
    }

    function makeService($user){
      $services = $user->services()->createMany(factory(App\Models\Service::class, 5)->make()->toArray());
      $services->map(function ($service) {
        try {
          $service->properties()->createMany(factory(App\Models\ServiceProperty::class, 3)->make()->toArray());
          // $service->update(['service_id' => $service->service()->inRandomOrder()->first()->id]);
        } catch (\Exception $e) {
          print($e->getMessage());
        }
      });
    }
}
