<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;
use App\Http\Controllers\MessageController;


class SupportController extends Controller
{
    public function __construct() {
      // $this->middleware('auth:api:admin')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      @[
        'orderBy'         => $orderBy,
        'order'           => $order,
        'pageSize'        => $pageSize,
        'user_id'         => $user_id,
        'conversation_id' => $conversation_id,
        'status'          => $status,
        'type'            => $type,
        'business_id'     => $business_id,
      ] = $request->validate([
        'orderBy'         => 'string',
        'order'           => 'in:asc,desc',
        'pageSize'        => 'int',
        'user_id'         => 'int',
        'conversation_id' => 'int',
        'status'          => 'in:Pending,Resolved,Reviewing',
        'type'            => 'in:Bug,Feature Request,How To',
        'business_id'     => 'int',
      ]);
      $user     = $request->user();

      return $user->supports()->whenWhere([
        'user_id'           => $user_id,
        'status'            => $status,
        'type'              => $type,
        'conversation_id'   => $conversation_id,
        'business_id'       => $business_id,
      ])
      ->orderBy($orderBy ?? 'id', $order ?? 'asc')
      ->paginate($pageSize);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      @[
        'title'   => $title,
        'message' => $message,
        'images'  => $images,
        'image'   => $image,
        'business_id'   => $business_id,
      ] = $request->validate([
        'message' => 'required',
        'title'   => 'required|min:3,max:70',
        'type'    => 'in:Bug,Feature Request,How To',
        'images'  => '',
        'image'   => '',
        'business_id'   => 'required|int',
      ]);

      $user     = $request->user();

      $conversation = $user->conversations()
        ->create(['user_id' => $user->id, 'name' => $title, 'type' => 'issue']);

      $message = $conversation->messages()
        ->create(['user_id' => $user->id, 'message' => $message]);

      $message->saveImage($images, 'images');
      $message->saveImage($image, 'image');

      $support = $conversation->support()
        ->create(['user_id' => $user->id] + $request->only(['title', 'type', 'business_id']));

      $support->conversation = $conversation;

      return $support;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function show(Support $support)
    {
      $this->authorize('view', $support);
      return $support;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Support $support)
    {
      $this->authorize('update', $support);
      $request->validate([]);
      $user     = $request->user();
      $support->update(array_filter($request->only($support->getFillable())));
      return $support;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Support  $support
     * @return \Illuminate\Http\Response
     */
    public function destroy(Support $support)
    {
      $this->authorize('delete', $support);
      $support->delete();
      return ['status' => true];
    }

    /**
     * Close the support ticket.
     *
     * @param  \App\Models\Support  $support
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function close(Support $support, Request $request)
    {
      $user = $request->user();
      $this->authorize('close', $support);
      $user->close($support);
      $support->conversation->makeDelete($user, all: true);
      return $support;
    }

    function join(Support $support, Request $request) {
      $user = $request->user();
      $this->authorize('join', $support);

      return $support->conversation->addMember($user);
    }
}
