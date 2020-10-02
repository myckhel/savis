<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerServiceVariation extends Model
{
  protected $fillable = ['customer_service_id', 'service_variation_id'];

  public function customerService(){
    return $this->belongsTo(CustomerService::class);
  }

  public function serviceVariation(){
    return $this->belongsTo(ServiceVariation::class);
  }
}
