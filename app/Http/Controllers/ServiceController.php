<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Business;
use App\Http\Requests\ServiceRequest;
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
         'business_id' => 'int',
         'orderBy'     => ['regex:(id|created_at|name)'],
       ]);

       $user = $request->user();

       $services = Service::when($request->business_id, fn ($q) => $q->whereBusinessId($request->business_id))
       ->search($request->search)
       ->orderBy($request->orderBy, $request->order)
                  ->paginate($request->pageSize);

        $services->map(function (Service $service) {
          $service->withUrls('logo');
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
    public function store(ServiceRequest $request)
    {
      $request->validate(['business_id' => 'required|int']);
      $user     = $request->user();
      $logo     = $request->logo;
      $businessUser = $user->businessUsing()->whereBusinessId($request->business_id)
      ->with('business')->firstOrFail();
      // $this->authorize('create' [Service::class, $business]);

      try {
        $service = $businessUser->business->createService($request);
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
       return $service->getProfile()->withUrls('logo');
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
    public function update(ServiceRequest $request, Service $service)
    {
      $request->validate([]);
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
       // $text = [];  $ids = $request->ids;
       // Service::whereIn('id', $ids)->delete();

       // return ['status' => true, 'text' => $text];
     }
}
