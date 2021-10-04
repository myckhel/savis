<?php

namespace App\Http\Controllers;

use App\Models\ServiceProperty;
use Illuminate\Http\Request;
use App\Models\Service;

class ServicePropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     public function store(Request $request){
       $request->validate([
         'service_id' => 'required',
         'metas'      => 'required|array',//[{name=> 'phone', rules => {required: true, file: cdr}}]
       ]);
       $service = Service::findOrFail($request->service_id);
       $this->authorize('create', [ServiceProperty::class, $service]);
       $creates = $request->metas;
       $serviceProperties = $service->properties()->createMany($creates);
       return ['status' => true, 'serviceProperties' => $serviceProperties];
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceProperty  $serviceProperty
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceProperty $serviceProperty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceProperty  $serviceProperty
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceProperty $serviceProperty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceProperty  $serviceProperty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceProperty $serviceProperty)
    {
      $serviceProperty->update($request->all());
      return $serviceProperty;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceProperty  $serviceProperty
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceProperty $serviceProperty)
    {
      return ['status' => $serviceProperty->delete()];
    }
}
