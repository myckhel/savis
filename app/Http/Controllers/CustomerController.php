<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\User;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Customer;
use App\Models\CustomerServiceMeta;
use App\Http\Requests\BusinessModelRequest;

class CustomerController extends Controller
{
  public function payments(Request $request, Customer $customer){
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
     $this->validatePagination($request, [
       'business_id' => 'int|required'
     ]);
     $user = $request->user();
     $businessUser = $user->businessUsing()->whereUserId($user->id)
     ->whereBusinessId($request->business_id)->firstOrFail();
     $business = $businessUser->business;
     $this->authorize('viewAnyCustomer', $user);
     $search = $request->search;

     $customers = $business->customers()->search($search)->orderBy(($request->orderBy ?? 'created_at'), 'DESC')
     ->paginate($request->pageSize);
     // $customers->each(function ($customer) {
     //   $customer->withImageUrl(null, 'avatar');
     // });
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
   public function store(BusinessModelRequest $request)
   {
     $user     = $request->user();
     $business = $user->businessUsing()->whereBusinessId($request->business_id)->firstOrFail();
     $business = $business->business;
     $email    = $request->email;
     $user_id  = $request->user_id;
     $customer = User::when(
       $email,
       fn ($q) => $q->whereEmail($email),
       fn ($q) => $q->whereId($user_id)
     )->firstOrCreate([],[
       'email' => $email,
     ]);

     return $business->customers()->firstOrCreate(
       ['user_id' => $customer->id],
       ['user_id' => $customer->id]
     );
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
     return $customer;
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
     $this->authorize('delete',$customer);
     return ['status' => $customer->delete()];
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
