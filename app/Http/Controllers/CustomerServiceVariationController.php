<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Business;
use App\Models\CustomerService;
use App\Models\CustomerServiceVariation;
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
    public function store(Request $request, $vars = [])
    {
      $request->validate([
        'business_id'           => 'int',
        'customer_service_id'   => 'required|int',
        'service_variations'    => 'required|array',
        'service_variations.*'  => 'int',
      ]);

      $business           = $vars['business'] ?? null;
      $service            = $vars['service'] ?? null;
      $customerService    = $vars['customerService'] ?? null; 
      $serviceVariations  = $vars['serviceVariations'] ?? null;

      if (!$business && $request->business_id) {
        $business = Business::findOrFail($request->business_id);
        $this->authorize('canWork', [$business, $service]);
      }

      $user = $request->user();
      $customer_service_id  = $request->customer_service_id;
      $service_variations   = $request->service_variations;
      $customerServiceVariations = [];

      if (!$customerService) {
        $customerService = $business->customerServices()->findOrFail($customer_service_id);
      }
      $serviceVariations = $serviceVariations ? $serviceVariations : $customerService->service->variations()->whereIn('id', $service_variations)->get();

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
