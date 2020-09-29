<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CanAttach;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Image;
use App\Traits\Helper;

class CustomerProperty extends Model implements HasMedia
{
  use InteractsWithMedia, CanAttach, Helper;

  public function authorizeMedia(Media $media, String $method, $user)
  {
    switch ($method) {
      case 'delete':
        if ($user->isCustomer()) {
          return $this->customer_id == $user->id;
        } else {
          $service = $this->service();
          return ($service) && $user->id == $service->user_id;
        }
        break;
    }
  }

  protected $fillable = ['service_property_id', 'customer_id', 'value'];
  protected $hidden = ['media'];

  public static function getProps($request, $user){
    $order  = $request->order;
    $search = $request->search;
    $props  = $user->properties();
    if ($search) $props->where('value', 'LIKE', '%'.$search.'%')
    ->orWhereHas('service_property', function($q) use($search){
      $q->where('name', 'LIKE', '%'.$search.'%');
    });
    $props->with(['service_property:id,name']);
    return $props->orderBy(($request->orderBy ?? 'created_at'), $order ?? 'DESC')->paginate($request->pageSize)->each(function ($data) {
      $data->withAttachedUrl('attachments');
    });
  }

  public function customer(){
    return $this->belongsTo(Customer::class);
  }

  public function service(){
    $id = $this->id;
    return Service::whereHas('properties', function ($q) use($id) {
      $q->whereHas('customer_properties', function ($q) use($id) {
        $q->where('id', $id);
      });
    })->first();
  }

  public function service_property(){
    return $this->belongsTo(ServiceProperty::class);
  }

  public function customer_service_properties(){
    return $this->hasMany(CustomerServiceProperty::class);
  }

  public function registerMediaCollections(Media $media = null) : void {
    $this->addMediaCollection('attachments')
    ->useDisk('attachments')
    ->registerMediaConversions($this->convertionCallback());
  }
}
