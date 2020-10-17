<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceVariation;
use Illuminate\Http\Request;

class ServiceVariationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->validate(['service_id' => 'required|int']);
      $user = $request->user();
      $service = Service::findOrFail($request->service_id);
      return $service->variations()
      ->with(['variation:id,name', 'service:id,name'])->paginate();
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
        'variation_id' => 'required|int',
        'service_id'   => 'required|int',
        'business_id'  => 'required|int',
      ]);

      $user       = $request->user();
      $business   = $user->findOrFailBusinessWhereHas($request->business_id, [
        'variation_id'  => $request->variation_id,
        'service_id'    => $request->service_id,
      ]);
      $create = [
        'variation_id' => $request->variation_id,
        'service_id'   => $request->service_id,
        'amount'       => $request->amount,
        'value'        => $request->value,
      ];
      return $business->serviceVariations()->create($create);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceVariation  $serviceVariation
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ServiceVariation $serviceVariation)
    {
      return $serviceVariation->load(['variation:id,name', 'service:id,name,price,charge']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceVariation  $serviceVariation
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceVariation $serviceVariation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceVariation  $serviceVariation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceVariation $serviceVariation)
    {
      $request->validate([
        'amount'       => 'required',
        'variation_id' => 'int',
        'service_id'   => 'int',
      ]);
      $user       = $request->user();
      $business   = $user->findOrFailBusinessWhereHas($request->business_id, [
        'variation_id'  => $request->variation_id,
        'service_id'    => $request->service_id,
      ]);
      $serviceVariation->update($request->all());
      return $serviceVariation;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceVariation  $serviceVariation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ServiceVariation $serviceVariation)
    {
      $user     = $request->user();
      $user->findOrFailBusinessWhereHas($request->business_id, [
        'service_variation_id' => $serviceVariation->id,
      ]);
      if ($serviceVariation->customerServiceVariations()->count()) {
        return ['status' => $serviceVariation->delete()];
      } else {
        return ['status' => $serviceVariation->forceDelete()];
      }
    }
}
