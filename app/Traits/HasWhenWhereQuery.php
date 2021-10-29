<?php
namespace App\Traits;

/**
 *
 */
trait HasWhenWhereQuery
{
  function scopeWhenWhere($q, array $columns) {
    collect($columns)->map(fn ($value, $column) =>
      $q->when($value, fn ($q) => $q->where($column, $value))
    );
  }
}
