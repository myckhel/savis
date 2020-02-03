<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CustomerProperty;
use App\CustomerServiceProperty;

class CustomerService extends Model
{
  protected $fillable = ['service_id', 'customer_id'];
  //
  public static function makeService($user, $request)
  {
    $service = Service::findOrFail($request->service_id);
    return $user->services()->create([
      'service_id' => $service->id,
    ]);
  }
  // relationship
  public function customer(){
    return $this->belongsTo(Customer::class);
  }
  public function property(){
    return $this->hasOne(CustomerServiceProperty::class);
  }
  public function properties(){
    return $this->hasMany(CustomerServiceProperty::class);
  }
  public function job(){
    return $this->hasOne(Work::class);
  }
  public function payment(){
    return $this->hasOne(Payment::class);
  }
  public function service(){
    return $this->belongsTo(Service::class);
  }
}
