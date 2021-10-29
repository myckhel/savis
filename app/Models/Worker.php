<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory, Searchable;
    protected $fillable = ['user_id', 'business_id'];
    protected $casts    = ['business_id' => 'int'];
    protected $searches = [];

    public function scopeWorkers($q, $business_id = null, $vars = []){
      $user_id = $vars['user_id'] ?? null;
      $q->whereHas('business',
        fn ($q) => $q->whereId($business_id)
        ->when($user_id,
          fn ($q) => $q->whereHas('workers',
            fn ($q) => $q->whereUserId($user_id)
          )
        )
      );
    }

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function business($business_id = null, $vars = [])
    {
      $user_id = $vars['user_id'] ?? null;
      return $this->belongsTo(Business::class)
      ->when($business_id,
        fn ($q) => $q->whereId($business_id)
      )->when($user_id,
        fn ($q) => $q->whereHas('workers',
          fn ($q) => $q->whereUserId($user_id)
        )
      );
    }
}
