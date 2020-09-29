<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Customer;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(User::class, 50)->create()->each(function ($user){
        $user->save();
      });
    }
}
