<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Customer;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Image;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Work extends Model implements HasMedia
{
  use InteractsWithMedia, HasFactory, HasImage;

  public function scopeRelatedTo($q, $user_id){
    $q->whereHasBusiness(
      fn ($q) => $q->where(
        fn ($q) => $q->whereHas('workers',
          fn ($q) => $q->whereUserId($user_id)
        )->orWhereHas('customers',
          fn ($q) => $q->whereUserId($user_id)
        )
      )
    );
  }

  public function scopeRelatedToWork($q, $user_id){
    $q->whereHasBusiness(
      fn ($q) => $q->whereHas('workers',
        fn ($q) => $q->whereUserId($user_id)
      )
    );
  }

  public function scopeWhereHasBusiness($q, $call = null){
    $q->whereHas('customerService',
      fn ($q) => $q->whereHas('service',
        fn ($q) => $q->whereHas('business', $call)
      )
    );
  }

  public function scopeBusiness($q, $business_id, $vars = []){
    $user_id = $vars['user_id'] ?? null;
    $q->whereHas('customerService',
      fn ($q) => $q->whereHas('service',
        fn ($q) => $q->whereHas('business',
          fn ($q) => $q->whereId($business_id)
          ->when($user_id,
            fn ($q) => $q->whereHas('workers',
              fn ($q) => $q->whereUserId($user_id)
            )
          )
        )
      )
    );
  }

  public static function countCompletedCustomerService(Customer $customer){
    $customer_services = $customer->customer_services->pluck('id');
    return self::where('status', 'completed')->whereIn('customer_service_id', $customer_services)->count();
  }

  public function authorizeMedia(Media $media, String $method, Model $user){

  }

  public function scopeSearch ($q, $search) {
    if ($search) {
      return $q->where(function ($q) use($search) {
        $q->where('status', $search);
      });
    }
    return $q;
  }

  protected $fillable = ['status'];
  protected $hidden = ['media'];

  public function customerService(){
    return $this->belongsTo(CustomerService::class);
  }

  public function registerMediaCollections(Media $media = null) : void {
    $this->addMediaCollection('attachments')
    ->useDisk('attachments')
    ->registerMediaConversions($this->convertionCallback());
  }
}
