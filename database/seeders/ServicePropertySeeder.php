<?php

use Illuminate\Database\Seeder;
use App\ServiceProperty;

class ServicePropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(ServiceProperty::class, 3)->create()->each(function ($service){
        $service->save();
      });
    }
}
