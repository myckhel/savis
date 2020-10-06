<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BusinessUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BusinessUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->validate([
        'orderBy'     => '',
        'search'      => 'min:3',
        'order'       => 'in:asc,desc',
        'pageSize'    => 'int',
      ]);
      $user     = $request->user();
      $pageSize = $request->pageSize;
      $order    = $request->order;
      $orderBy  = $request->orderBy;
      $search   = $request->search;

      return $user->businessUsing()->search($search)
      ->orderBy($orderBy ?? 'id', $order ?? 'asc')
      ->paginate($pageSize);
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
        'business_id' => 'required|int',
        'user_id'     => [
          'exists:users,id',
          Rule::requiredIf(fn () => !$request->email)
        ],
        'email'   => 'email|exists:users,email',
      ]);
      $user     = $request->user();
      $business = $user->ownedBusinesses()->findOrFail($request->business_id);
      $email    = $request->email;
      $user_id  = $request->user_id;
      $businessUser = User::when(
        $email,
        fn ($q) => $q->whereEmail($email),
        fn ($q) => $q->whereId($user_id)
      )->first();

      return $business->users()->firstOrCreate(
        ['user_id' => $businessUser->id],
        ['user_id' => $businessUser->id]
      );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BusinessUser  $businessUser
     * @return \Illuminate\Http\Response
     */
    public function show(BusinessUser $businessUser)
    {
      $this->authorize('view', $businessUser);
      return $businessUser;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BusinessUser  $businessUser
     * @return \Illuminate\Http\Response
     */
    public function edit(BusinessUser $businessUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BusinessUser  $businessUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BusinessUser $businessUser)
    {
      $this->authorize('update', $businessUser);
      $request->validate([]);
      $user     = $request->user();
      $businessUser->update(array_filter($request->only($businessUser->getFillable())));
      return $businessUser;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BusinessUser  $businessUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(BusinessUser $businessUser)
    {
      $this->authorize('delete', $businessUser);
      return ['status' => $businessUser->delete()];
    }
}
