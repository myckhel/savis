<?php

use Illuminate\Database\Seeder;
use App\CustomerServiceProperty;

class CustomerServicePropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(CustomerServiceProperty::class, 1)->create()->each(function ($cservicep){
        $p = $cservicep->customer_service()->service()->properties()->get();
      });
    }
}
