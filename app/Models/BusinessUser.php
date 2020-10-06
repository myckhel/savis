<?php

namespace App\Models;

use App\Traits\HasMeta;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessUser extends Model
{
    use HasFactory, Searchable, HasMeta;
    protected $fillable = ['user_id', 'business_id'];
    protected $casts    = ['business_id' => 'int'];
    protected $searches = [];

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function business()
    {
      return $this->belongsTo(Business::class, 'business_id');
    }
}
