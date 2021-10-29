<?php

namespace App\Models;

use App\Traits\HasWhenWhereQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;

class Support extends Model
{
    use HasFactory, HasWhenWhereQuery;
    protected $fillable = ['title', 'user_id', 'conversation_id', 'type', 'status', 'closer_id', 'closed_at', 'business_id'];
    protected $casts    = ['closed_at' => 'datetime', 'business_id' => 'int'];

    function user() {
      return $this->belongsTo(User::class);
    }

    function closer() {
      return $this->belongsTo(User::class, 'closer_id');
    }

    function conversation() {
      return $this->belongsTo(Conversation::class);
    }
}
