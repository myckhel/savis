<?php

namespace App\Models;

use App\User;
use App\Traits\HasImage;
use Myckhel\ChatSystem\Models\Conversation as BaseConversation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Conversation extends BaseConversation implements HasMedia
{
  use InteractsWithMedia, HasImage;
  protected $hidden = ['pivot', 'media'];

  function makeAvatar($url) {
    if ($url) {
      $this->avatar = new \stdClass();
      $this->avatar->thumb   = $url->thumb;
      $this->avatar->medium  = $url->medium;
      $this->avatar->url     = $url->url;
    }
  }

  function addMember(User $member) {
    return $this->participants()->create(['user_id' => $member->id]);
  }

  function support() {
    return $this->hasOne(Support::class);
  }
}
