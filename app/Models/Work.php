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

class Work extends Model implements HasMedia
{
  use InteractsWithMedia, HasImage;
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

  public function customer_service(){
    return $this->belongsTo(CustomerService::class);
  }

  public function registerMediaCollections(Media $media = null) : void {
    $this->addMediaCollection('attachments')
    ->useDisk('attachments')
    ->registerMediaConversions($this->convertionCallback());
  }
}