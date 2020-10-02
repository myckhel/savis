<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
  protected $fillable = [/*'user_id',*/'name'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public function serviceVariations()
  {
    return $this->hasMany(ServiceVariation::class);
  }
}
