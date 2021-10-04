<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $this->call(UserSeeder::class);
      $this->call(CustomerTableSeeder::class);
      $this->call(CustomSeeder::class);
      // exclude $this->call(ServiceTableSeeder::class);
      // exclude $this->call(CustomerServiceTableSeeder::class);
      // exclude $this->call(PaymentTableSeeder::class);
      // x $this->call(WorkTableSeeder::class);
      $this->call(UserCustomerSeeder::class);
      $this->call(CustomerPropertySeeder::class);
    }
}
