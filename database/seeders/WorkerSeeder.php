<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Worker;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Worker::factory()->times(3)->create();
    }
}
