<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CustomerProperty;
use App\CustomerServiceProperty;
use App\Http\Controllers\CustomerPropertyController;

class CustomerService extends Model
{
  protected $fillable = ['service_id', 'customer_id'];

  public function getAmount()
  {
    // get charges
    $charges = $this->getCharges();
    return $charges + $this->amount;
  }

  function getCharges()
  {
    return $this->charge ?? $this->parent ? $this->parent->charge ?? 0 : 0;
  }

  public static function makeService($customer, $request)
  {
    $service = Service::findOrFail($request->service_id);
    $props   = null;
    $customerServiceProperties = null;

    if ($request->properties) {
      $control = new CustomerPropertyController;
      $props   = $control->store($request);
    }

    $customerService = $customer->services()->create([
      'service_id' => $service->id,
    ]);

    if ($props) {
      $props->each(fn ($p) => $p->customer_property_id = $p->id);

      $customerServiceProperties = $customerService->properties()->createMany($props->toArray());
    }

    $job                        = $customerService->job()->create();
    $customerService->job       = $job;
    $customerService->service   = $service;
    $customerService->properties   = $customerServiceProperties;

    return $customerService;
  }
  // relationship
  public function customer(){
    return $this->belongsTo(Customer::class);
  }
  public function property(){
    return $this->hasOne(CustomerServiceProperty::class);
  }
  public function properties(){
    return $this->hasMany(CustomerServiceProperty::class);
  }
  public function job(){
    return $this->hasOne(Work::class);
  }
  public function payment(){
    return $this->hasOne(Payment::class);
  }
  public function service(){
    return $this->belongsTo(Service::class);
  }
}
