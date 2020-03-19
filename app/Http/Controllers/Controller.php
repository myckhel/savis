<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function paginate($request, $size = 20){
      return $request->pageSize ? $request->pageSize : $size;
    }

    public function validatePagination($request, $custom = []){
      if (!$request->orderBy) $request->orderBy = 'created_at';
      if (!$request->order) $request->order = 'asc';
      return $request->validate(array_merge_recursive([
        'search'   => 'nullable',
        'pageSize' => 'nullable|integer',
        'orderBy'  => 'nullable',
        'order'    => ['regex:(asc|desc)'],
      ], $custom));
    }
}
