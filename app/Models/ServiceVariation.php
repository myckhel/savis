<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceVariation extends Model
{
  protected $fillable = ['service_id', 'variation_id', 'value', 'amount'];
  protected $casts = ['amount' => 'float', 'service_id' => 'int', 'variation_id' => 'int'];

  public function service(){
    return $this->belongsTo(Service::class);
  }
  public function customerServiceVariations(){
    return $this->hasMany(CustomerServiceVariation::class);
  }

  public function variation(){
    return $this->belongsTo(Variation::class);
  }
}
