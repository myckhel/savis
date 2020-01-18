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
       // $request->validate([]);
       $this->validatePagination($request);
       $service = new Service;
       if ($search = $request->search)
         $service = $service->where('name', 'LIKE', '%'.$search.'%');

       return $service->orderBy(($request->orderBy ?? 'created_at'), 'Desc')
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

      try {
        $service = $user->createService($request);
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
     public function show($id)
     {
       $service = Service::find($id);
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
     public function destroy($id)
     {
       //
       $service = Service::find($id);
       if ($service) {
         $service->delete();
         return ['status' => true];
       }
       return ['status' => false];
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
