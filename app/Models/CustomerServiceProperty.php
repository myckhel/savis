<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerServiceProperty extends Model
{
  use HasFactory;
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
