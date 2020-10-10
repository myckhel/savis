<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
  protected $fillable = [/*'user_id',*/'name'];

  public function business()
  {
    return $this->belongsTo(Business::class);
  }
  public function serviceVariations()
  {
    return $this->hasMany(ServiceVariation::class);
  }
}
