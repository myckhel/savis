<?php
namespace App\Traits;

/**
 *
 */
trait CanAttach
{
  public function saveAttachments($attachments, $collection, $getMedia=false){
    $medias = [];
    foreach ($attachments as $attachment) {
      $medias[] = $this->uploadAttachments($attachment, $collection);
      // $customerProperty->saveAttachments($attachment, 'attachments', true);
    }
    if ($getMedia) {
      return $medias;
    } else {
      return $this->withAttachedUrl($collection, $medias);
    }
  }

  public function withAttachedUrl($collection, $medias = null){
    if (!$medias) $medias = $this->getMedia($collection);

    if ($medias) {
      $attachments = [];
      for ($i=0; $i < sizeof($medias); $i++) {
        $attachments[] = $this->attachedObj($medias[$i]);
      }
      if ($attachments) $this->$collection = $attachments;
    }
    return $this;
  }

  private function attachedObj($media){
    $attachments = new \stdClass();
    $attachments->url = $media->getUrl();
    $attachments->metas = $media->custom_properties;
    $attachments->metas['id'] = $media->id;
    return $attachments;
  }

  public function uploadAttachments($attachments, $collection){
    return $this->addMedia($attachments)
    ->usingName($collection)->toMediaCollection($collection);
  }
}
