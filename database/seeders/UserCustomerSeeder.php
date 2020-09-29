<?php

use Illuminate\Database\Seeder;
use App\UserCustomer;

class UserCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(UserCustomer::class, 500)->create()->each(function ($customer){
        $customer->save();
      });
    }
}
