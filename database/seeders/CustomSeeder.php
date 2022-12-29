<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerService;

class CustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // seed customers
      $this->seedUsers();
    }

    public function seedUsers(){
      $users = User::all();
      $users->map(function($user){
        $customers = Customer::inRandomOrder()->limit(12)->get();
        $user->customers()->attach( $customers );
      });
    }

    public function seedCustomers(){
      $customers = Customer::limit(1)->get();
      $customers->map(function($customer){
        $customer_services = $customer->services()->createMany(factory(CustomerService::class, 2)->make()->toArray());
        $customer_services->map(function($service){
          $service->job()->save(factory(App\Models\Work::class)->make());
          $service->payment()->save(factory(App\Models\Payment::class)->make());
        });
      });
    }
}
