<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media as SpatieMedia;
use App\CustomerProperty;

class Media extends SpatieMedia
{
  public function destroyMedia()
  {
    $model = $this->model;
    $user = auth()->user();
    $bool = $model->authorizeMedia($this, 'delete', $user);
    if ($bool) {
      return $this->delete();
    } else {
      throw new \Exception(trans('msg.media.forbidden'), 501);
    }
  }

  public function authorize(String $method)
  {
    $user = auth()->user();
    if ($user->isCustomer()) {

    } else {

    }

    $model_type     = $this->model_type;
    switch ($model_type) {
      case CustomerProperty::class:

        break;

      default:
        break;
    }

  }
}