<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerProperty extends Model
{
  protected $fillable = ['service_property_id', 'customer_id', 'value'];

  public static function getProps($request, $user){
    $order  = $request->order;
    $search = $request->search;
    $props  = $user->properties();
    if ($search) $props->where('value', 'LIKE', '%'.$search.'%')
    ->orWhereHas('service_property', function($q) use($search){
      $q->where('name', 'LIKE', '%'.$search.'%');
    });
    $props->with(['service_property:id,name']);
    return $props->orderBy(($request->orderBy ?? 'created_at'), $order ?? 'DESC')->paginate($request->pageSize);
  }

  public function customer(){
    return $this->belongsTo(Customer::class);
  }

  public function service(){
    $id = $this->id;
    return Service::whereHas('properties', function ($q) use($id) {
      $q->whereHas('customer_properties', function ($q) use($id) {
        $q->where('id', $id);
      });
    })->first();
  }

  public function service_property(){
    return $this->belongsTo(ServiceProperty::class);
  }

  public function customer_service_properties(){
    return $this->hasMany(CustomerServiceProperty::class);
  }
}
