<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
  protected $fillable = ['business_id','name'];
  protected $casts    = ['business_id' => 'int'];

  public function business()
  {
    return $this->belongsTo(Business::class);
  }
  public function serviceVariations()
  {
    return $this->hasMany(ServiceVariation::class);
  }
}
