<?php
namespace App\Interfaces;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
/**
 *
 */
interface canMedia
{
  public function authorizeMedia(Media $media, String $method, Model $user)
}

?>
