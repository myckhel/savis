<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory, Searchable;
    protected $fillable = ['user_id', 'name', 'email', 'category_id'];
    protected $casts    = ['category_id' => 'int'];
    protected $searches = ['name', 'email', 'category_id'];

    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
