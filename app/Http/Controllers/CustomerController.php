<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\User;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Customer;
use App\Models\CustomerServiceMeta;
use App\Http\Controllers\MetaController;

class CustomerController extends Controller
{
  public function getMeta(Request $request){
    $control = new MetaController;
    return $control->index($request);
  }

  public function addMeta(Request $request){
    $control = new MetaController;
    return $control->store($request);
  }

  public function payments(Request $request, Customer $customer){
    // $customer = Customer::findOrFail($customer);
    $histories = $customer->payments()->get();

    return ['status' => true, 'message' => null, 'histories' => $histories];
  }

  public function jobs(Request $request, $customer){
    $customer = Customer::findOrFail($customer);
    $histories = $customer->jobs()->get();

    return ['status' => true, 'message' => null, 'histories' => $histories];
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
   public function index(Request $request)
   {
     $this->validatePagination($request);
     $user = $request->user();
     $this->authorize('viewAnyCustomer', $user);
     // $customer = new Customer;//$user->customers();
     $search = $request->search;

     $customers = $user->customers()->search($search)->orderBy(($request->orderBy ?? 'created_at'), 'DESC')
     ->paginate($request->pageSize);
     $customers->each(function ($customer) {
       $customer->withImageUrl(null, 'avatar');
     });
     return $customers;
   }
   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create(Request $request)
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
     $user = $request->user();
     if ($c_id = $request->customer_id) {
       $customer = Customer::findOrFail($c_id);
       if ($attached = $user->customers()->find($customer->id)) {
         return ['status' => false, 'message' => trans('msg.customer.is_attached')];
       }
       $user->customers()->attach($customer->id);
       return ['status' => true, 'message' => trans('msg.customer.added'), 'customer' => $customer];
     } else {
       $request->validate([
         'firstname'    => 'required|max:35|min:3',
         'lastname'     => 'string|max:35|min:3',
         'phone'        => 'phone|numeric|min:6',//|max:15',
         'email'        => 'email',
         'country_code' => '',
         'city'         => 'max:45',
         'lat'          => '',
         'lng'          => '',
         'state'        => 'max:45',
         'address'      => 'nullable',
         'country'      => 'nullable'
       ]);

       try {
         $email         = $request->email;
         $customer      = Customer::where('email', $email)->first();
         if ($customer) {
           if ($attached = $user->customers()->find($customer->id)) {
             return ['status' => false, 'message' => trans('msg.customer.is_attached')];
           }
           $request->validate(['email'        => 'required|email|unique:customers,email']);
           $user->customers()->attach($customer->id);
           return ['status' => true, 'message' => trans('user.created')];
         } else {
           $customer = Customer::addNew($request);
           $user->customers()->attach($customer->id);
           return ['status' => true, 'message' => trans('msg.customer.added'), 'customer' => $customer];
         }
       } catch (\Exception $e) {
         return ['status' => false, 'message' => $e->getMessage()];
       }
     }
   }
   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show(Customer $customer)
   {
     $this->authorize('view', $customer);
     return $customer->withImageUrl(null, 'avatar');
   }
   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
     //
     return Customer::findOrFail($id);
   }
   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, Customer $customer)
   {
       $this->authorize('update', $customer);
       $request->validate(['updates' => 'required|array']);
       $updates = $request->updates;
       $avatar  = $request->avatar;
       try {
         $customer->update(array_filter($updates));
       } catch (\Exception $e) {
         return response()->json(['status' => false, 'message' => trans('msg.update.failed')], 400);
       }
       ($avatar) && $customer->saveImage($avatar, 'avatar');
       return $customer;
   }
   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy(Customer $customer)
   {
     $deleted;
     if ($customer) {
       $deleted = $customer->delete();
     }
     return ['status' => !!$deleted];
   }

   public function delete(Request $request)
   {
     $request->validate([
      'ids' => 'required'
     ]);

     $text = [];  $ids = $request->ids;
     if ($ids) {
       foreach ($ids as $id) {
         if ($customer = Customer::find($id) ) {
           $customer->delete();
         } else {
           $text[] = $id;
         }
       }
       return ['status' => true, 'message' => $text];
     }
     return ['status' => false, 'message' => 'Invalid Request Data'];
   }

   public function profile(Customer $customer){
     $this->authorize('view', $customer);
     $profile = $customer->jobsStatusCount();
     return ['profile' => $profile];
   }

   public function properties(Customer $customer){
     $this->authorize('view', $customer);
     $customer_services = $customer->services()->with(['properties.service_property', 'service'])->get();
     return $customer_services;
   }

}
