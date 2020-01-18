<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CustomerServiceServiceProperty;

class ServiceProperty extends Model
{
  protected $fillable = [ 'service_id', 'name', 'rule'];
  //
  public function service(){
    return $this->belongsTo(Service::class);
  }

  public function customer_properties(){
    return $this->hasMany(CustomerProperty::class);
  }
  public function customer_service_properties(){
    return $this->hasMany(CustomerServiceProperty::class);
  }
}
