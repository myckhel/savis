<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMeta;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Image\Image;
use App\Traits\HasImage;
use App\Traits\User\Role;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable implements HasMedia
{
    use Role, Notifiable, HasApiTokens, SoftDeletes, HasMeta, HasMediaTrait, HasImage;

    function findCustomer(Customer $customer = null, $email = null){
      if(!$customer && !$email) return null;

      return $this->customers()
      ->when($customer, fn ($q) => $q->where('customer_id', $customer->id ?? $customer))
      ->when($email, fn ($q) => $q->orWhere('email', $email) )
      ->first();
    }

    public function addCustomer($customer = null, $email = null)
    {
      $customer_id = $customer->id ?? $customer;
      if ($customer || $email) {
        $customer = $this->findCustomer($customer, $email);
      }

      if (!$customer) {
        $customer = Customer::lookOrFail($customer_id, $email);
        $this->customers()->attach($customer->id);
      }
      return $customer;
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
      return $this->services()->create($create);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'activation_token', 'lat', 'lng'
    ];
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token', 'media'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isCustomer()
    {
      return false;
    }

    public function isAdmin()
    {
      return true;
    }

    public function properties() {
      $id = $this->id;
      return CustomerProperty::whereHas('customer', function ($q) use($id) {
        $q->whereHas('clients', function ($q) use($id) {
          $q->where('user_id', $id);
        });
      });
    }

    public function authorizeMedia(Media $media, String $method, Model $user){
      return $media->model_id == $user->id && $media->model_type == get_class($user);
      // $this->can('delete', $medis);
    }

    public function jobs() {
      $id = $this->id;
      return Work::whereHas('customer_service', function ($q) use($id) {
        $q->whereHas('customer', function ($q) use($id) {
          $q->whereHas('clients', fn ($q) => $q->where('user_id', $id));
        })
        ->whereHas('service', fn ($q) => $q->where('user_id', $id));
      });
    }

    public function customers(){
      return $this->belongsToMany(Customer::class, UserCustomer::class)->withTimestamps();
    }

    public function metas(){
      return $this->morphMany(Meta::class, 'metable');
    }

    // public function customer_services(){
    //   return $this->customers->services()->count();
    //   return $this->hasManyThrough(CustomerService::class, UserCustomer::class);
    // }

    public function payments(){
      return $this->customers->services;
    }

    public function services(){
      return $this->hasMany(Service::class);
    }

    public function customerServices(){
      return $this->hasManyThrough(CustomerService::class, Service::class);
    }
    public function variations(){
      return $this->hasMany(Variation::class);
    }
    // public function customerServiceVariations(){
    //   return $this->hasManyThrough(ServiceVariation::class, CustomerService::class, 'customer_id', 'service_id');
    // }
    public function serviceVariations(){
      return $this->hasManyThrough(ServiceVariation::class, Service::class);
    }

    public function registerMediaCollections(Media $media = null){
      $this->addMediaCollection('avatar')
      ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
      ->singleFile()->useDisk('user_images');
    }

    public function registerMediaConversions(Media $media = null){
      $this->addMediaConversion('thumb')
      ->width(368)->height(232)
      ->performOnCollections('avatar');
    }
}
