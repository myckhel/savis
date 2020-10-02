<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\CustomerServiceServiceProperty;
use \Illuminate\Database\Eloquent\Collection;

class ServiceProperty extends Model
{
  protected $fillable = [ 'service_id', 'name', 'rules'];
  protected $casts = ['rules' => 'array'];

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

  public function newCollection(array $models = Array()){
    return new CustomCollection($models);
  }
}

/**
*
*/
class CustomCollection extends Collection {
  public function keyValue()
  {
    $metas = $this->items;
    $meta = [];
    foreach ($metas as $value) {
      $name = $value->name;
      $meta[$name] = $value;
    }
    $this->items = $meta;
    return $meta;
  }
}
