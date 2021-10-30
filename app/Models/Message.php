<?php

namespace App\Models;

use App\Traits\HasVideo;
use App\Traits\HasImage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Myckhel\ChatSystem\Models\Message as BaseMessage;

class Message extends BaseMessage implements HasMedia
{
  use InteractsWithMedia, HasVideo, HasImage;

  function latestMedia(){
    return $this->morphOne(Media::class, 'model')->latest();
  }

  function registerMediaCollections(): void{
    $this->addMediaCollection('image')
    ->acceptsMimeTypes($this->mimes)
    ->singleFile()->useDisk('msg_images')
    ->registerMediaConversions($this->convertionCallback(true));

    $this->addMediaCollection('images')
    ->acceptsMimeTypes($this->mimes)->useDisk('msg_images')
    ->registerMediaConversions($this->convertionCallback(true));

    $this->addMediaCollection('videos')
    ->acceptsMimeTypes($this->v_mimes)->useDisk('msg_videos')
    ->registerMediaConversions($this->videoConvertionCallback());
  }
}
