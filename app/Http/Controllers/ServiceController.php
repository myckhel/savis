<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
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

       $service = Service::allLeaves();

       if ($search = $request->search)
         $service->where('name', 'LIKE', '%'.$search.'%');

       return $service->orderBy($request->orderBy, $request->order)
              ->paginate($request->pageSize);
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
       return $service->getProfile();
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
      $update = $service->update($request->all());
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
       //
       $text = [];  $ids = $request->ids;
       foreach ($ids as $id) {
         if ($service = Service::find($id) ) {
           $service->delete();
         } else {
           $text[] = $id;
         }
       }

       return ['status' => true, 'text' => $text];
     }
}
