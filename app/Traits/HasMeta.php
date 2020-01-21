<?php
namespace App\Traits;
/**
 *
 */
trait HasMeta
{
  public function addMeta($check = [], $metas)
  {
    $metas = $this->metas()->updateOrCreate($check, $metas);
    $this->load('metas');
    return $metas;
  }
}
