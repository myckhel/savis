<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Work;

class WorkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //
      Work::factory(1)->create()->each(function ($work){
        $work->save();
      });
    }
}
