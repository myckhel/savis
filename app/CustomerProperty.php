<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerProperty extends Model
{
  protected $fillable = ['service_property_id', 'customer_id', 'value'];

  public function customer(){
    return $this->belongsTo(Customer::class);
  }

  public function service_property(){
    return $this->belongsTo(ServiceProperty::class);
  }
  public function customer_service_properties(){
    return $this->hasMany(CustomerServiceProperty::class);
  }
}
