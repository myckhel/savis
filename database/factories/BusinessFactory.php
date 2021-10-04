<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Business::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        return [
          'user_id' => $user ? $user->id : null,
          'name'    => $this->faker->word,
          'email'   => $this->faker->safeEmail,
        ];
    }

    public function user($id)
    {
      return $this->state(fn (array $attributes) => [
        'user_id' => $id
      ]);
    }
}
