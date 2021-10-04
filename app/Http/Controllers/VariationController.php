<?php

namespace App\Http\Controllers;

use App\Models\Variation;
use Illuminate\Http\Request;

class VariationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->validate(['business_id' => 'int']);
      $user         = $request->user();
      $business_id  = $request->business_id;
      $business     = $business_id ? $user->findOrFailBusiness($business_id) : null;
      return $business->variations()->paginate();
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
        'name'        => 'required',
        'business_id' => 'required|int',
      ]);
      $user     = $request->user();
      $business = $user->findOrFailBusiness($request->business_id);

      return $business->variations()->create([
        'name' => $request->name,
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Variation  $variation
     * @return \Illuminate\Http\Response
     */
    public function show(Variation $variation)
    {
      $this->authorize('view', $variation);
      return $variation;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Variation  $variation
     * @return \Illuminate\Http\Response
     */
    public function edit(Variation $variation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Variation  $variation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Variation $variation)
    {
      $this->authorize('update', $variation);
      $variation->update($request->only($variation->getFillable()));
      return $variation;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Variation  $variation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Variation $variation)
    {
      $this->authorize('delete', $variation);
      if ($variation->serviceVariations()->count()) {
        // SoftDeletes
        $variation->delete();
        return ['status' => false];
      } else {
        $variation->forceDelete();
        return ['status' => true];
      }
    }
}
