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

    public function findWorker($id)
    {
      return $this->workers()->whereUserId($id)->first();
    }
    public function scopeFindWorker($q, $id)
    {
      $q->whereUserId($id)->first();
    }

    public function createService($request){
      $create = [
        'name'    => $request->name,
        'price'   => $request->price,
        'charge'  => $request->charge,
      ];

      if ($service_id = $request->service_id) {
        $service = Service::findOrFail($service_id);
        $create['service_id'] = $service->id;
        return $this->services()->create($create);
      }
      return $this->services()->firstOrCreate($create, $create);
    }

    public function services(){
      return $this->hasMany(Service::class);
    }

    public function owner()
    {
      return $this->belongsTo(User::class, 'user_id');
    }
    public function serviceVariations(){
      return $this->hasManyThrough(ServiceVariation::class, Service::class);
    }

    public function workers(){
      return $this->hasMany(BusinessUser::class);
    }
    public function customers(){
      return $this->hasMany(Customer::class);
    }
}
