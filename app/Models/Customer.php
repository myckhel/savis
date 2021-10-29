<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Work;
use Payment;
use Service;
use ServiceMeta;
use UserCustomer;
use CustomerServiceMeta;
use Carbon\Carbon;
use App\Traits\User\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Casts\Jsonable;

class Customer extends Authenticatable
{
  use Role, HasFactory;

  public static function lookOrFail($customer_id = null, $email = null){
    if(!$customer_id && !$email) return null;

    return self::when($customer_id, fn ($q) => $q->where('id', $customer_id))
    ->when($email, fn ($q) => $q->orWhereHas('user', fn ($q) => $q->where('email', $email) ))
    ->firstOrFail();
  }
  // public static function lookOrCreate($customer_id = null, $email = null){
  //   if(!$customer_id && !$email) return null;
  //
  //   $customer = self::when($customer_id, fn ($q) => $q->where('id', $customer_id))
  //   ->when($email, fn ($q) => $q->orWhereHas('user', fn ($q) => $q->where('email', $email) ))
  //   ->first();
  //   return $customer ? $customer : User::create(['email' => $email])->;
  // }

  protected $fillable = ['user_id', 'business_id', 'metas'];
  protected $casts    = ['metas' => Jsonable::class];
  protected $hidden   = [];

  public function authorizeMedia(Media $media, String $method, Model $user){
    return $media->model_id == $user->id && $media->model_type == get_class($user);
  }

  public function grantMeToken($request = null){
    $token = $this->createToken('PAT');

    return [
      'access_token' => $token->plainTextToken,
      'token_type' => 'Bearer',
    ];
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

  public function scopeSearch($q, $search)
  {
    if ($search) {
      return $q->whereHas('user', function ($q) use($search) {
        $q->where('firstname', 'LIKE', '%'.$search.'%')->orWhere('lastname', 'LIKE', '%'.$search.'%')
        ->orWhere('phone', 'LIKE', '%'.$search.'%')->orWhere('email', 'LIKE', '%'.$search.'%');
      });
    }
    return $q;
  }

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

  public function serviceProperties($service_id = null){
    return $this->hasManyThrough(CustomerServiceProperty::class, CustomerService::class)
    ->when($service_id, fn ($q) => $q->whereHas('customerService',
      fn ($q) => $q->whereHas('service', fn ($q) => $q->where('id', $service_id))
    ));
  }

  // public function clients(){
  //   return $this->belongsToMany(Business::class);
  // }
  public function client(){
    return $this->belongsTo(Business::class, 'business_id');
  }
  public function user(){
    return $this->belongsTo(User::class, 'user_id');
  }

  public function business(){
    return $this->belongsTo(Business::class, 'business_id');
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

  public function registerMediaCollections(Media $media = null) : void {
    $this->addMediaCollection('avatar')
    ->useFallbackUrl('https://www.pngitem.com/middle/hhmRJo_profile-icon-png-image-free-download-searchpng-employee')
    ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
    ->singleFile()->useDisk('customer_images')
    ->registerMediaConversions($this->convertionCallback());
  }
}
