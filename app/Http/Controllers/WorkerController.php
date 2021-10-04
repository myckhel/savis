<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Worker;
use App\Models\Business;
use App\Http\Requests\BusinessModelRequest;
use Illuminate\Http\Request;

class WorkerController extends Controller
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
        'business_id' => 'int',
      ]);
      $user     = $request->user();
      $pageSize = $request->pageSize;
      $order    = $request->order;
      $orderBy  = $request->orderBy;
      $search   = $request->search;
      $business_id   = $request->business_id;

      return Worker::workers($business_id, ['user_id' => $user->id])
      ->search($search)
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
    public function store(BusinessModelRequest $request)
    {
      $user     = $request->user();
      $business = $user->ownedBusinesses()->findOrFail($request->business_id);
      $email    = $request->email;
      $user_id  = $request->user_id;
      $worker = User::when(
        $email,
        fn ($q) => $q->whereEmail($email),
        fn ($q) => $q->whereId($user_id)
      )->firstOrCreate([
        'email' => $email,
      ]);

      return $business->workers()->firstOrCreate(
        ['user_id' => $worker->id],
        ['user_id' => $worker->id]
      );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function show(Worker $worker)
    {
      $this->authorize('view', $worker);
      return $worker;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function edit(Worker $worker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Worker $worker)
    {
      $this->authorize('update', $worker);
      $request->validate([]);
      $user     = $request->user();
      $worker->update(array_filter($request->only($worker->getFillable())));
      return $worker;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function destroy(Worker $worker)
    {
      $this->authorize('delete', $worker);
      return ['status' => $worker->delete()];
    }
}
