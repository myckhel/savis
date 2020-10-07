<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\CustomerProperty;
use App\CustomerServiceProperty;
use App\Http\Controllers\CustomerPropertyController;
use App\Http\Controllers\CustomerServiceVariationController;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerService extends Model
{
  use HasFactory;
  protected $fillable = ['service_id', 'customer_id'];

  public function getAmount()
  {
    // get charges
    $charges = $this->getCharge();
    return $charges + $this->service->price;
  }

  function getCharge()
  {
    return $this->service->getCharge();
  }

  public static function makeService($customer, $request)
  {
    $service = Service::findOrFail($request->service_id);
    $props   = null;
    $customerServiceProperties = null;
    $serviceVariations = null;

    if ($request->service_variations) {
      $serviceVariations = $service->variations()->whereIn('id', $request->service_variations)->get();
    }
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

    if ($serviceVariations) {
      $control = new CustomerServiceVariationController;
      $request->merge(['customer_service_id' => $customerService->id]);
      $customerServiceVariations   = $control->store($request);
    }

    $client                       = $service->user;
    $client->addCustomer($customer);
    $job                          = $customerService->job()->create();
    $customerService->job         = $job;
    $customerService->service     = $service;
    $customerService->properties  = $customerServiceProperties;
    $customerService->variations  = $customerServiceVariations;

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
  public function variations(){
    return $this->hasMany(CustomerServiceVariation::class);
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
