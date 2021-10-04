<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->validate([
        'orderBy'     => ['regex:(id|created_at)'],
        'order'       => ['regex:(desc|asc)'],
        'business_id' => 'int',
      ]);

      $user = $request->user();
      return Work::business($request->business_id, ['user_id', $user->id])
      ->search($request->search)
      ->orderBy($request->orderBy ?? 'id', $request->order ?? 'asc')
      ->paginate();
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
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function show(Work $job)
    {
      $this->authorize('view', $job);
      return $job;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function edit(Work $work)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Work $job)
    {
      $this->authorize('update', $job);
      $request->validate([
        'status'        => ['required', 'regex:(pending|completed|canceled|on hold|processing|failed)'],
        'attachments'   => 'array',
        'attachments'   => 'array:file',
      ]);

      $attachments = $request->file('attachments');

      $update = $job->update(['status' => $request->status]);
      if ($update && $attachments) {
        foreach ($attachments as $attachment) {
          $job->addMedia($attachment)->usingName('attachments')->toMediaCollection('attachments');
        }
      }
      return ['status' => !!$update, 'job' => $job];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function destroy(Work $work)
    {
        //
    }
}
