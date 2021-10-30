<?php
namespace App\Traits;
use \Illuminate\Database\Eloquent\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use \getID3;

/**
 *
 */
trait HasVideo
{
  protected $v_mimes = ['video/x-flv', 'video/mp4', 'video/MP2T', 'video/3gpp', 'video/quicktime', 'video/x-msvideo', 'video/x-ms-wmv'];

  private function formatBytes($size, $precision = 2){
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');

    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
  }
  public function saveVideo($videos, $collection){
    if ($videos) {
      $medias = [];
      if (is_array($videos)) array_map(
        fn ($video) => $medias[] = $this->uploadVideo($video, $collection),
        $videos
      ); else $medias[] = $this->uploadVideo($videos, $collection);

      $this->withUrls($collection, false, $medias);
      return $this;
    }
  }

  public function uploadVideo($video, $collection){
    $getID3 = new getID3;
    $media = $this->addMedia($video)
    ->usingName($collection)
    ->withCustomProperties([
      'size' => $this->formatBytes($video->getSize()),
    ])
    ->toMediaCollection($collection);
    $info = $getID3->analyze($media->getPath());
    $duration = date('H:i:s.v', $info['playtime_seconds']);
    $media->setCustomProperty('length', $duration);
    $media->save();
    return $media;
  }

  private function videoConvertionCallback(){
    return (fn (Media $media = null) =>
      $this->addMediaConversion('thumb')
      ->width(368)->height(232)
      ->extractVideoFrameAtSecond(0.7)
    );
  }
}
