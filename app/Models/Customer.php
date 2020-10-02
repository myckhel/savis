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
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Traits\HasMeta;
use Spatie\MediaLibrary\File;
use Spatie\Image\Image;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Traits\User\Role;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;

class Customer extends Authenticatable implements HasMedia
{
  use Role, Notifiable, HasApiTokens, SoftDeletes, HasMeta, InteractsWithMedia, HasImage;

  public static function lookOrFail($customer_id = null, $email = null){
    if(!$customer_id && !$email) return null;

    return self::when($customer_id, fn ($q) => $q->where('id', $customer_id))
    ->when($email, fn ($q) => $q->orWhere('email', $email) )
    ->firstOrFail();
  }
  public static function lookOrCreate($customer_id = null, $email = null){
    if(!$customer_id && !$email) return null;

    return self::when($customer_id, fn ($q) => $q->where('id', $customer_id))
    ->when($email, fn ($q) => $q->orWhere('email', $email) )
    ->firstOrCreate(['email' => $email]);
  }

  protected $fillable = ['firstname', 'lastname', 'email', 'phone', 'state', 'city','address','country', 'lat', 'lng',
    'password', 'activation_token'
  ];
  protected $hidden = ['pivot',
    'password', 'remember_token', 'activation_token', 'media'
  ];

  public function authorizeMedia(Media $media, String $method, Model $user){
    return $media->model_id == $user->id && $media->model_type == get_class($user);
  }

  public function grantMeToken($request = null){
    $token = $this->createToken('PAT');
    // if ($request && $request->remember_me)
    //     $token->expires_at = Carbon::now()->addWeeks(1);

    return [
      'access_token' => $token->plainTextToken,
      'token_type' => 'Bearer',
    ];
  }

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

  public function scopeSearch($q, $search)
  {
    if ($search) {
      return $q->where(function ($q) use($search) {
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

  public function isCustomer()
  {
    return true;
  }

  public function isAdmin()
  {
    return false;
  }

  // relationship
  public function services(){
    return $this->hasMany(CustomerService::class);
  }

  public function service_properties(){
    return $this->hasManyThrough(CustomerServiceProperty::class, CustomerService::class);
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
    return $this->morphMany(Meta::class, 'metable');
  }

  public function registerMediaCollections(Media $media = null) : void {
    $this->addMediaCollection('avatar')
    ->useFallbackUrl('https://www.pngitem.com/middle/hhmRJo_profile-icon-png-image-free-download-searchpng-employee')
    ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
    ->singleFile()->useDisk('customer_images')
    ->registerMediaConversions($this->convertionCallback());
  }
}
