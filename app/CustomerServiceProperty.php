<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerServiceProperty extends Model
{
  protected $fillable = ['customer_service_id', 'customer_property_id', 'service_property_id'];

  public static function getCredantials(Customer $customer){
    return $customer->services()->with('properties')->get();
  }

  public function customer_service(){
    return $this->belongsTo(CustomerService::class);
  }
  public function service_property() {
    return $this->belongsTo(ServiceProperty::class);
  }
  public function customer_property() {
    return $this->belongsTo(CustomerProperty::class);
  }
}
