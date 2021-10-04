<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerProperty;
use App\Models\CustomerServiceProperty;
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
