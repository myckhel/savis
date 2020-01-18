<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Work;
use App\Payment;
use App\Service;
use App\ServiceMeta;
use App\UserCustomer;
use App\CustomerServiceMeta;

class Customer extends Model
{
    //
    protected $fillable = ['firstname', 'lastname', 'email', 'phone', 'state', 'city','address','country', 'lat', 'lng'];
    protected $hidden = ['pivot'];

  public static function addNew($request){
    return self::create([
      'firstname'   => $request->firstname,
      'lastname'    => $request->lastname,
      'email'       => $request->email,
      'phone'       => $request->phone,
      'state'       => $request->state,
      'city'        => $request->city,
      'address'     => $request->address,
      'country'     => $request->country,
      'lat'         => $request->lat,
      'lng'        => $request->lng,
    ]);
  }

  public static function checkUnique($field, $request){
    return self::where($field, $request->$field)->first();
  }

  public function jobsStatusCount(){
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
    ]);
    return $this;
  }

  // public function properties(){
  //   return $this->credentialsWithServices();
  // }

  public function credentialsWithServices(){
    $services = [];
    $credentials = CustomerServiceMeta::getCredantials($this);
    return $credentials;

    if ($credentials) {
      $metas = ServiceMeta::with('services')->whereIn('id', $credentials->pluck('id'))->with('services')->get();
      if ($metas) {
        $metas->map(function($meta, $i) use(&$services, $credentials) {
          $service = $meta->services;
          if ($service) {
            $services[] = [
              $service->name => []
            ];
            $services[sizeof($services)-1][$service->name][] = [
              $meta->name => $credentials[$i]->value
            ];
          }
        });
      }
    }
    return $services;
  }

  // relationship
  public function services(){
    return $this->hasMany(CustomerService::class);
  }

  public function clients(){
    return $this->belongsToMany(Customer::class, 'user_customers');
  }

  public function payments(){
    return $this->hasManyThrough(Payment::class, CustomerService::class);
  }

  public function properties(){
    return $this->hasMany(CustomerProperty::class);
  }

  public function jobs(){
    return $this->hasManyThrough(Work::class, CustomerService::class);
  }

  public function metas(){
    return $this->hasMany(CustomerMeta::class);
  }
}
