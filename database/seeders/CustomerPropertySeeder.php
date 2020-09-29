<?php

use Illuminate\Database\Seeder;
use App\CustomerProperty;

class CustomerPropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(CustomerProperty::class, 500)->create()->each(function ($property){
        $property->save();
      });
    }
}
