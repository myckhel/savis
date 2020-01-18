<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Customer;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $user = $request->user();
      return $user->with('metas')->paginate($this->paginate($request));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
      $this->authorize('view', $user);
      return $user;
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
      $this->authorize('update', $user);
      $request->validate(['updates' => 'required|array']);
      $updates = $request->updates;
      $user->update($updates);
      return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
      $this->authorize('delete', $user);
      $user->delete();
      return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, User $user)
    {
      $this->authorize('restore', $user);
      return $user->restore();
    }

    public function destroyCustomer(Request $request, Customer $customer)
    {
      $this->authorize('remove', $customer);
      $user = $request->user();

      $user->customers()->detach($customer);

      return ['status' => true, 'message' => trans('msg.removed')];
    }

    public function addCustomer(Request $request, Customer $customer){
      $this->authorize('add', $customer);
      $user = $request->user();
      $user->customers()->attach($customer);
      return ['status' => true, 'message' => trans('msg.added')];
    }

    public function current(Request $request)
    {
      $user = $request->user('api');
      if ($user) return [ 'status' => true, 'user' => $user];

      return [ 'status' => false, 'text' => 'No Authenticated User' ];
    }

    public function stats(Request $request){
      $user = $request->user();

      $user->loadCount(['customers']);
      return ['status' => true, 'user' => $user];
    }
}
