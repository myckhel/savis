<?php
namespace App\Traits;

/**
 *
 */
trait HasImage
{
  public function saveImage($image, $collection, $getMedia=false){
    $medias = [];
    if (\is_array($image))
      foreach ($image as $img)
        $medias[] = $this->uploadImage($img, $collection);
    else $medias = $this->uploadImage($image, $collection);
    if ($getMedia) {
      return $medias;
    } else {
      return $this->withImageUrl($medias, $collection, \is_array($image));
    }
  }

  public function withImageUrl($medias = null, $collection, $is_array = false){
    if (!$medias) $medias = $is_array ? $this->getMedia($collection) : $this->getFirstMedia($collection);

    if ($medias) {
      $images = [];
      if ($is_array) {
        $images = [];
        for ($i=0; $i < sizeof($medias); $i++) {
          $images[] = $this->imageObj($medias[$i]);
        }
      } else {
        $images = $this->imageObj($medias);
      }
      if ($images) $this->$collection = $images;
    }
    return $this;
  }

  private function imageObj($media){
    $image = new \stdClass();
    $image->thumb = $media->getUrl('thumb');
    $image->url = $media->getUrl();
    $image->metas = $media->custom_properties;
    return $image;
  }

  public function uploadImage($image, $collection){
    return $this->addMediaFromBase64($image)
    ->usingName($collection)->toMediaCollection($collection);
  }
}
