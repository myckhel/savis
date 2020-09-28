<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Baum\NestedSet\Node as WorksAsNestedSet;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Image\Image;
use App\Traits\HasImage;

class Service extends Model implements HasMedia
{
  use WorksAsNestedSet, SoftDeletes, HasMediaTrait, HasImage;

  function getCharge()
  {
    return (int)($this->charge ?? ($this->parent ? $this->parent->charge ?? 0 : 0));
  }

  protected $parentColumnName = 'service_id';
  // protected $casts = ['properties' => 'array'];
  // protected $depthColumnName = 'depth';
  public function fields()
  {
    $parent         = $this->service;
    $parentProps    = $parent->properties->keyValue();
    $props          = $this->properties->keyValue();
    return array_merge($parentProps, $props);
  }
  public function authorizeMedia(Media $media, String $method, Model $user){

  }
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

  public static function scopeSearch($stmt, $search){
    if ($search) {
      return $stmt->where(function ($stmt) use($search) {
        $stmt->where('name', 'LIKE', '%'.$search.'%');
      });
    }
    return $stmt;
  }

  protected $fillable = [ 'name', 'price', 'charge', 'service_id', 'user_id' ];
  protected $hidden = [ 'media' ];

  public static function checkUnique($filed, $request){
    return self::where($filed, $request->$filed)->first();
  }
  // relationship
  public function metas(){
    return $this->morphMany(Meta::class);
  }

  public function services(){
    return $this->hasMany(Service::class);
  }
  public function variations(){
    return $this->hasMany(ServiceVariation::class);
  }
  public function customerServiceVariations(){
    return $this->hasMany(CustomerServiceVariation::class);
  }
  public function service(){
    return $this->belongsTo(Service::class);
  }
  public function user(){
    return $this->belongsTo(User::class);
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

  public function registerMediaCollections(Media $media = null){
    $this->addMediaCollection('logo')
    ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
    ->singleFile()->useDisk('service_images');
  }

  public function registerMediaConversions(Media $media = null){
    $this->addMediaConversion('thumb')
    ->width(368)->height(232)
    ->performOnCollections('logo');
  }
}
