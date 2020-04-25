<?php

namespace App\Http\Controllers;

use App\ServiceProperty;
use App\CustomerProperty;
use Illuminate\Http\Request;

class CustomerPropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $this->validatePagination($request);
      $user   = $request->user();
      return CustomerProperty::getProps($request, $user);
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
        'service_property_id' => 'required|int',
        'value'               => 'required',
        'attachments'         => 'array:file',
      ]);

      $authUser = $request->user();
      $value = $request->value;
      $attachments = $request->file('attachments');
      $service_property_id = $request->service_property_id;
      $prop = ServiceProperty::findOrFail($service_property_id);

      if ($authUser->isCustomer()) {
        $customer = $authUser;
      } else {
        $user = $authUser;
        $request->validate(['customer_id' => 'required|int']);
        $customer = $user->customers()->findOrFail($request->customer_id);
        // auth user can attach properties for his customer service
        $this->authorize('attach', $prop);
      }

      $toCreate = [
        'service_property_id' => $prop->id,
        'value'               => $value
      ];
      $customerProperty = $customer->properties()->updateOrCreate(
        $toCreate, $toCreate
      );

      ($customerProperty && $attachments) && $customerProperty->saveAttachments($attachments, 'attachments', true);
      return $customerProperty->withAttachedUrl('attachments');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomerProperty  $customerProperty
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, CustomerProperty $customerProperty)
    {
      return $customerProperty;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomerProperty  $customerProperty
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerProperty $customerProperty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomerProperty  $customerProperty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerProperty $customerProperty)
    {
      $this->authorize('update', $customerProperty);
      $request->validate([
        'value'               => 'required',
        'attachments'         => 'array:file',
      ]);

      $customer = $request->user();
      $value = $request->value;
      $attachments = $request->file('attachments');
      $update = $customerProperty->update(['value' => $request->value]);

      ($update && $attachments) && $customerProperty->saveAttachments($attachments, 'attachments', true);

      return ['status' => true, 'customer_property' => $customerProperty->withAttachedUrl('attachments')];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomerProperty  $customerProperty
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerProperty $customerProperty)
    {
      $this->authorize('delete', $customerProperty);
      return ['status' => $customerProperty->delete()];
    }
}
