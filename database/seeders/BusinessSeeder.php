<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Business;
// use App\Models\User;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Business::factory()->times(3)
      ->create();
    }
}
