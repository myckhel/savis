<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
  // use SoftDeletes;
  public function getProfile(){
    $this->loadCount([
      // 'services',
      'jobs as jobs_completed' => function($q){
        $q->where('status', 'completed');
      },
      'jobs as jobs_pending' => function($q){
        $q->where('status', 'pending');
      },
      'jobs as jobs_on_hold' => function($q){
        $q->where('status', 'on hold');
      },
      'jobs as jobs_failed' => function($q){
        $q->where('status', 'failed');
      }
    ])->load(['services', 'service', 'properties']);
    // $this->completed_jobs_count = Job::countCompletedCustomerService($this);
    // $this->completed_payments_count = Payment::countCompletedCustomerService($this);
    // $this->credentialServices = $this->credentialsWithServices();
    return $this;
  }

  protected $fillable = [ 'name', 'price', 'charge', 'service_id', 'user_id' ];

  public static function checkUnique($filed, $request){
    return self::where($filed, $request->$filed)->first();
  }
  // relationship
  public function services(){
    return $this->hasMany(Service::class);
  }
  public function service(){
    return $this->belongsTo(Service::class);
  }
  public function customer_services(){
    return $this->hasMany(CustomerService::class);
  }
  public function properties(){
    return $this->hasMany(ServiceProperty::class);
  }

  public function jobs(){
    return $this->hasManyThrough(Work::class, CustomerService::class);
  }

  public function payments(){
    return $this->hasManyThrough(Payment::class, CustomerService::class);
  }
}
