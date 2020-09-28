<?php

namespace App\Http\Controllers;

use App\CustomerServiceVariation;
use Illuminate\Http\Request;

class CustomerServiceVariationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      // $user = $request->user();
      // return $user->customerServiceVariations()->paginate();
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
        'customer_service_id'   => 'required|int',
        'service_variations'    => 'required|array',
        'service_variations.*'  => 'int',
      ]);

      $user = $request->user();
      $customer_service_id  = $request->customer_service_id;
      $service_variations   = $request->service_variations;
      $customerServiceVariations = [];

      if ($user->getRole() == 'user') {
        $customerService = $user->customerServices()->findOrFail($customer_service_id);
        $serviceVariations = $customerService->service->variations()->whereIn('id', $service_variations)->get();
        // $this->validate('create', [CustomerServiceVariation::class, $customerService, $serviceVariations]);
      } else {
        $customerService = $user->services()->findOrFail($customer_service_id);
        $serviceVariations = $customerService->service->variations()->whereIn('id', $service_variations)->get();
      }
      if ($serviceVariations) {
        $serviceVariations->each(function ($p) use($customerService) {
          $p->service_variation_id = $p->id;
          $p->customer_service_id = $customerService->id;
        });
        $customerServiceVariations = $customerService->variations()->createMany($serviceVariations->toArray());
      }
      return $customerServiceVariations;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerServiceVariation  $customerServiceVariation
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerServiceVariation $customerServiceVariation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerServiceVariation  $customerServiceVariation
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerServiceVariation $customerServiceVariation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerServiceVariation  $customerServiceVariation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerServiceVariation $customerServiceVariation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerServiceVariation  $customerServiceVariation
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerServiceVariation $customerServiceVariation)
    {
        //
    }
}
