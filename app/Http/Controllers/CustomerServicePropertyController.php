<?php

namespace App\Http\Controllers;

use App\Models\CustomerServiceProperty;
use App\Models\Customer;
use App\Models\User;
use App\Models\Service;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerServicePropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->validate([
        'business_id' => 'int',
        'service_id'  => 'int',
        'customer_id' => ['int', Rule::requiredIf(fn () => $request->business_id && !$request->user_id)],
        'user_id'     => ['int', Rule::requiredIf(fn () => $request->business_id && !$request->customer_id)],
      ]);
      $customer_id  = $request->customer_id;
      $business     = $request->business_id ? Business::findOrFail($request->business_id) : null;
      $service      = $request->service_id  ? Service::findOrFail($request->service_id) : null;

      $customer     = $business->customers()
      ->when($request->user_id, fn ($q) => $q->whereUserId($request->user_id))
      ->when($customer_id, fn ($q) => $q->whereId($customer_id))
      ->firstOrFail();
      $this->authorize('canWork', [$business, $service]);

      return $customer->serviceProperties($request->service_id)->paginate();
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
        'business_id'             => 'int',
        'customer_service_id'     => 'int',
        'service_property_ids'    => 'array',
        'service_property_ids.*'  => 'int',
        'customer_property_ids'   => 'array',
        'customer_property_ids.*' => 'int',
      ]);
      $customer_service_id    = $request->customer_service_id;
      $customer_property_ids  = $request->customer_property_ids;
      $service_property_ids   = $request->service_property_ids;
      $business_id            = $request->business_id;
      $user                   = $request->user();
      $business               = $business_id ? $user->findOrFailBusiness($business_id) : null;

      $customerService        = $business->customerServices()->findOrFail($customer_service_id);
      $creates                = $this->toMany($customer_property_ids, $service_property_ids, $customerService->customer_id);
      return $customerService->properties()->createMany($creates);
    }

    private function toMany($cpids, $spids, $customer_id){
      foreach ($cpids as $key => $value) {
        $prop['customer_property_id'] = $value;
        $prop['service_property_id']  = $spids[$key];
        $prop['customer_id']          = $customer_id;
        $creates[]                    = $prop;
      }
      return $creates;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerServiceProperty  $customerServiceProperty
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerServiceProperty $customerServiceProperty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerServiceProperty  $customerServiceProperty
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerServiceProperty $customerServiceProperty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerServiceProperty  $customerServiceProperty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerServiceProperty $customerServiceProperty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerServiceProperty  $customerServiceProperty
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerServiceProperty $customerServiceProperty)
    {
        //
    }
}
