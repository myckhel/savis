<?php

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
      // $this->call(ServiceTableSeeder::class);
      // $this->call(CustomerServiceTableSeeder::class);
      // $this->call(PaymentTableSeeder::class);
      // $this->call(WorkTableSeeder::class);
      // $this->call(UserCustomerSeeder::class);
      // $this->call(CustomerPropertySeeder::class);
    }
}
