<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\File;
use Spatie\Image\Image;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Traits\HasImage;
use App\Traits\User\Role;
use UserCustomer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Casts\Jsonable;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, HasApiTokens, Role, InteractsWithMedia, Notifiable, SoftDeletes, HasImage;

    public function findOrFailBusiness($business_id, $call = null){
      return $this->businessUsing($business_id)->with('business')->when($call, $call)->firstOrFail()->business;
    }
    public function findOrFailBusinessWhereHas($business_id, $vars = [], $call = null){
      $variation_id         = $vars['variation_id'] ?? null;
      $service_id           = $vars['service_id'] ?? null;
      $service_variation_id = $vars['service_variation_id'] ?? null;

      return $this->findOrFailBusiness(
        $business_id,
        fn ($q)     => $q->whereHas('business',
          fn ($q)   => $q->when($variation_id, fn ($q) => $q->whereHas('variations',
            fn ($q) => $q->whereId($variation_id)
          ))
          ->when($service_id || $service_variation_id,
            fn ($q)     => $q->whereHas('services',
              fn ($q)   => $q->when($service_id,
                fn ($q) => $q->whereId($service_id),
                fn ($q) => $q->whereHas('variations', fn ($q) => $q->whereId($service_variation_id)),
              )
            )
          )
        ),
        $call,
      );
    }
    public function findBusiness($business_id){
      $bUser = $this->businessUsing($business_id)->first();
      return $bUser ? $bUser->business : null;
    }

    public function hasVariation($variation_id){
      return $this->businessUsing()
      ->whereHas('business', fn ($q) =>
        $q->whereHas('variations', fn ($q) => $q->whereId($variation_id))
      )->first();
    }

    function findCustomer($customer = null, $email = null){
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

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'activation_token', 'lat', 'lng', 'metas'
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
        'email_verified_at' => 'datetime', 'metas' => Jsonable::class
    ];

    public function properties($business_id = null) {
      return $this->hasManyThrough(CustomerProperty::class, Customer::class)
      ->when($business_id, fn ($q) => $q->whereHas('customer', fn ($q) =>
        $q->whereHas('business', fn ($q) =>
          $q->whereId($business_id)
        ))
      );
    }

    public function authorizeMedia(Media $media, String $method, Model $user){
      return $media->model_id == $user->id && $media->model_type == get_class($user);
      // $this->can('delete', $medis);
    }

    public function customers(){
      return $this->belongsToMany(Customer::class, UserCustomer::class)->withTimestamps();
    }

    // public function customer_services(){
    //   return $this->customers->services()->count();
    //   return $this->hasManyThrough(CustomerService::class, UserCustomer::class);
    // }

    public function ownedBusinesses(){
      return $this->hasMany(Business::class);
    }
    public function businessUsing($business_id = null){
      return $this->hasMany(Worker::class, 'user_id')
      ->when($business_id, fn ($q) => $q->whereBusinessId($business_id));
    }
    public function workers($business_id = null, $vars = []){
      $user_id = $vars['user_id'] ?? null;
      return $this->hasMany(Worker::class, 'user_id')
      ->when($business_id, fn ($q) => $q->whereBusinessId($business_id))
      ->when($user_id,
        fn ($q) => $q->whereHas('business',
          fn ($q) => $q->whereHas('workers',
            fn ($q) => $q->whereUserId($user_id)
          )
        )
      );
    }
    // public function businesses(){
    //   return $this->hasMany(Worker::class);
    // }

    public function customerServices(){
      return $this->hasManyThrough(CustomerService::class, Service::class);
    }
    public function variations(){
      return $this->hasMany(Variation::class);
    }
    // public function customerServiceVariations(){
    //   return $this->hasManyThrough(ServiceVariation::class, CustomerService::class, 'customer_id', 'service_id');
    // }

    public function registerMediaCollections(Media $media = null) : void {
      $this->addMediaCollection('avatar')
      ->useFallbackUrl('https://www.pngitem.com/middle/hhmRJo_profile-icon-png-image-free-download-searchpng-employee')
      ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
      ->singleFile()->useDisk('user_images')
      ->registerMediaConversions($this->convertionCallback());
    }
}
