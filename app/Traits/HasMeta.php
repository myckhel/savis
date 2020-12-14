<?php
namespace App\Traits;
/**
 *
 */
trait HasMeta
{
  public function addMeta($metas, $check = [])
  {
    $metas = $this->metas()->updateOrCreate($check, $metas);
    $this->load('metas');
    return $metas;
  }
}
