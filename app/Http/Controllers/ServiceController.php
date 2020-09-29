<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
  public function fields(Request $request, Service $service)
  {
    return $service->fields();
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
       $this->validatePagination($request, [
         'orderBy' => ['regex:(id|created_at|name)'],
       ]);

       $user = $request->user();

       $services = $user->services()->search($request->search);

       $services = $services->orderBy($request->orderBy, $request->order)
                  ->paginate($request->pageSize);

        $services->map(function (Service $service) {
          $service->withImageUrl(null, 'logo');
        });
        return $services;
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
        'name'        => 'required|min:3|max:45',
        'service_id'  => 'numeric|nullable',
        'charge'      => '',
        'price'       => 'numeric|nullable',
        'logo'        => '',
      ]);
      $user = $request->user();
      $logo = $request->logo;

      try {
        $service = $user->createService($request);
        ($service && $logo) && $service->saveImage($logo, 'logo');
        return ['status' => true, 'service' => $service];
      } catch (\Exception $e) {
        return ['status' => false, 'text' => $e->getMessage()];
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
     public function show(Service $service)
     {
       return $service->getProfile()->withImageUrl(null, 'logo');
     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      return Service::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
      $request->validate([
        'name'        => 'min:3|max:45',
        'service_id'  => 'numeric|nullable',
        'charge'      => '',
        'price'       => 'numeric',
        'logo'        => '',
      ]);
      $this->authorize('update', $service);
      $user = $request->user();
      $logo = $request->logo;

      $update = $service->update($request->all());
      ($update && $logo) && $service->saveImage($logo, 'logo');

      return ["status" => $update, 'service' => $service];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
     public function destroy(Request $request, Service $service)
     {
       $this->authorize('delete', $service);
       $service->delete();
       return ['status' => true];
     }

     public function delete(Request $request)
     {
       $request->validate([
         'ids' => 'required|array'
       ]);

       // $this->authorize('deleteMulti', $service);
       $text = [];  $ids = $request->ids;
       Service::whereIn('id', $ids)->delete();

       return ['status' => true, 'text' => $text];
     }
}
