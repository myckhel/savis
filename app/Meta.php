<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
  protected $fillable = ['name', 'value'];
  public function meta(){
    return $this->morphTo();
  }
}
