<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Business;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Worker::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
      $business = Business::inRandomOrder()->first();
      $user     = User::inRandomOrder()->first();
      return [
        'user_id'     => $user->id,
        'business_id' => $business->id,
      ];
    }
}
