<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;

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