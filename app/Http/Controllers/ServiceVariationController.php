<?php

namespace App\Http\Controllers;

use App\Service;
use App\ServiceVariation;
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
      $user = $request->user();
      if ($user->getRole() == 'user') {
        return $user->serviceVariations()
        ->with(['variation:id,name', 'service:id,name'])->paginate();
      } else {
        $request->validate(['service_id' => 'required|int']);
        $service = Service::findOrFail($request->service_id);
        return $service->variations()
        ->with(['variation:id,name', 'service:id,name'])->paginate();
      }
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
      ]);

      $user       = $request->user();
      $variation  = $user->variations()->findOrFail($request->variation_id);
      $service    = $user->services()->findOrFail($request->service_id);
      return $user->serviceVariations()->create([
        'variation_id' => $variation->id,
        'service_id'   => $service->id,
        'amount'       => $request->amount,
        'value'        => $request->value,
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceVariation  $serviceVariation
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ServiceVariation $serviceVariation)
    {
      $user = $request->user();
      if($user->getRole() === 'user'){
        $this->authorize('view', $serviceVariation);
        return $serviceVariation->load(['variation:id,name', 'service:id,name,price,charge']);
      } else {
        return $serviceVariation->load(['variation:id,name', 'service:id,name,price,charge']);
      }
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
      $request->validate(['amount' => 'required']);
      $this->authorize('update', $serviceVariation);
      $serviceVariation->update($request->only(['amount']));
      return $serviceVariation->unsetRelations();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceVariation  $serviceVariation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceVariation $serviceVariation)
    {
      $this->authorize('delete', $serviceVariation);
      if ($serviceVariation->customerServiceVariations()->count()) {
        return ['status' => false];
        // return ['status' => $serviceVariation->delete()];
      } else {
        return ['status' => $serviceVariation->forceDelete()];
      }
    }
}
