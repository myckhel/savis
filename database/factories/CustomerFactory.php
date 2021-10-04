<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use App\Models\Work;
use App\Models\Business;
use App\Models\CustomerService;
use App\Models\ServiceProperty;
use App\Models\CustomerProperty;
use Faker\Generator as Faker;
use Faker\Provider\Payment;
use App\Models\CustomerServiceProperty;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 *
 */
class CustomerFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Customer::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    $business = Business::inRandomOrder()->first();
    $user     = User::inRandomOrder()->first();
    return [
      'user_id'     => $user->id,
      'business_id' => $business->id,
    ];
  }

  // public function configure()
  // {
      // return $this->afterCreating(function (Customer $customer) {
      //   $customer_services = $customer->services()->createMany(CustomerService::factory(), 7)->make()->toArray();
      //   $customer_services->map(function($customer_service)use($customer){
      //     $service = $customer_service->service()->first();
      //     $service_properties = $service->properties()->get();
      //     $service_properties_index = 0;
      //
      //     $customer_service->job()->create(Work::factory())->make()->toArray();
      //     $customer_service->payment()->create(Payment::factory())->make()->toArray();
      //
      //     if ($service_properties) {
      //       $customer_service->property()->createMany(CustomerServiceProperty::factory(), $service_properties->count())->make([
      //         'customer_property_id' => CustomerProperty::factory()->create([
      //             'customer_id' => $customer->id,
      //             'service_property_id' => $service->first()->id,
      //             'value' => $this->faker->word(5, 10),
      //           ])->toArray()['id'],
      //           'service_property_id' => $service_properties[$service_properties_index++]->id
      //         ])->toArray();
      //     }
      //   });
      // });
  // }
}
