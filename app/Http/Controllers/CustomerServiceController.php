<?php

namespace App\Http\Controllers;

use App\CustomerService;
use App\Customer;
use Illuminate\Http\Request;

class CustomerServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
         //
         return Customer::with('customer_service.customer_service_metas')->get();
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
         //
     }
     /**
      * Display the specified resource.
      *
      * @param  \App\CustomerService  $customerService
      * @return \Illuminate\Http\Response
      */
     public function show(CustomerService $customerService)
     {
         //
         return CustomerService::find($customerService->id);
     }
     /**
      * Show the form for editing the specified resource.
      *
      * @param  \App\CustomerService  $customerService
      * @return \Illuminate\Http\Response
      */
     public function edit(CustomerService $customerService)
     {
         //
     }
     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  \App\CustomerService  $customerService
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, CustomerService $customerService)
     {
         //
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerService  $customerService
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerService $customerService)
    {
        //
    }
}
