<?php

namespace App\Http\Controllers;

use App\Models\CustomerService;
use App\Models\Customer;
use App\Models\User;
use App\Models\Service;
use App\Models\Business;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomerPropertyController;
use App\Http\Controllers\CustomerServiceVariationController;

class CustomerServiceController extends Controller
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
          'customer_id' => 'int|exists:customers,id',
          'user_id'     => 'int|exists:users,id',
        ]);

        $business = Business::findOrFail($request->business_id);
        $this->authorize('canWork', $business);

        return $business->customerServices($request->customer_id, $request->user_id)
        ->paginate();
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
         'business_id'          => 'int',
         'service_id'           => 'required|int',
         'customer_id'          => 'int',
         'service_variations'   => 'array',
         'service_variations.*' => 'int',
         'user_email'           => 'email',
         'user_id'              => 'int',
       ]);

       $user = $request->user();
       $service = Service::with('business')->whereId($request->service_id)->firstOrFail();
       $business = $service->business;

       if ($request->business_id) {
         $this->authorize('canWork', [$business, $service]);

         if ($customer_id = $request->customer_id) {
           $customer =  $business->customers($customer_id)->firstOrFail();
         } elseif ($email = $request->user_email) {
           $userc = User::firstOrCreate(['email' => $email], ['email' => $email]);
           $customer = $business->customers()
           ->firstOrCreate(['user_id' => $userc->id], ['user_id' => $userc->id]);
         } elseif ($user_id = $request->user_id){
           $userc = User::findOrFail($user_id);
           $customer = $business->customers()
           ->firstOrCreate(['user_id' => $userc->id], ['user_id' => $userc->id]);
         }
       } else {
         $customer = $business->addCustomer(['user_id' => $user->id]);
       }

       $customerService = $customer->services()->create([
         'service_id' => $service->id,
       ]);
       $customerServiceProperties = null;
       $serviceVariations = null;
       $props   = null;

       if ($request->properties) {
         $control = new CustomerPropertyController;
         $props   = $control->store($request);
         if ($props) {
           $props->each(fn ($p) => $p->customer_property_id = $p->id);
           $customerServiceProperties = $customerService->properties()->createMany($props->toArray());
         }
       }

       if ($request->service_variations) {
         $serviceVariations = $service->variations()->whereIn('id', $request->service_variations)->get();
         if ($serviceVariations) {
           $control = new CustomerServiceVariationController;
           $request->merge(['customer_service_id' => $customerService->id]);
           $customerServiceVariations   = $control->store($request,
            ['business' => $business, 'service' => $service, 'customerService' => $customerService]
          );
         }
       }

       $customerService->variations  = $customerServiceVariations;
       $customerService->properties  = $customerServiceProperties;
       $customerService->service     = $service;
       return $customerService->load('job');
     }
     /**
      * Display the specified resource.
      *
      * @param  \App\CustomerService  $customerService
      * @return \Illuminate\Http\Response
      */
     public function show(CustomerService $customerService)
     {
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
