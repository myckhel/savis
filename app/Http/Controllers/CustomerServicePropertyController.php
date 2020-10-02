<?php

namespace App\Http\Controllers;

use App\Models\CustomerServiceProperty;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerServicePropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $user           = $request->user();
      $customer_id    = $request->customer_id;
      $customer       = Customer::findOrFail($customer_id);
      return $customer->service_properties()->paginate();
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
      $user_id                = $request->user_id;
      $customer_service_id    = $request->customer_service_id;
      $customer_property_ids  = $request->customer_property_ids;
      $service_property_ids   = $request->service_property_ids;
      $customer_id            = $request->customer_id;
      $user                   = $request->user();

      $customer               = $user->isCustomer() ? $user : Customer::findOrFail($customer_id);

      $customerService        = $customer->services()->findOrFail($customer_service_id);
      $creates                = $this->toMany($customer_property_ids, $service_property_ids, $customer_id);
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
