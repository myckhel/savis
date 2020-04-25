<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Customer;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Image\Image;
use App\Traits\HasImage;

class Work extends Model implements HasMedia
{
  use HasMediaTrait, HasImage;
  public static function countCompletedCustomerService(Customer $customer){
    $customer_services = $customer->customer_services->pluck('id');
    return self::where('status', 'completed')->whereIn('customer_service_id', $customer_services)->count();
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

  public function registerMediaCollections(Media $media = null){
    $this->addMediaCollection('attachments')
    // ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif'])
    ->useDisk('attachments');
  }
}
