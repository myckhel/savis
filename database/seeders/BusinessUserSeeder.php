<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusinessUser;

class BusinessUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      BusinessUser::factory()->times(3)->create();
    }
}
