<?php

namespace App\Http\Controllers;

use App\Models\ServiceProperty;
use App\Models\CustomerProperty;
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
        'properties'          => 'required|array',
        'customer_id'         => 'int',
        'customer_email'      => 'email',
        'properties.values'   => 'required|array',
        'properties.service_property_ids'   => 'required|array',
        'properties.service_property_ids.*' => 'int',
      ]);
      // todo either fields required

      $authUser          = $request->user();
      $properties        = $request->properties;
      $customer_id       = $request->customer_id;
      $email             = $request->email;
      $customer          = $authUser->isCustomer() ? $authUser : $authUser->findCustomer($customer_id, $email);

      $creates = [];
      $len     = sizeof($properties);
      for ($key=0; $key < $len; $key++) {
        // $attachments  = $request->file('attachments');
        $value               = $properties['values'][$key];
        $service_property_id = $properties['service_property_ids'][$key];
        // validate all fields

        // serial
        $creates[] = [
          'value'               => $value,
          'service_property_id' => $service_property_id,
          'customer_id'         => $customer_id,
        ];
      }

      if ($authUser->isCustomer()) {
        $customer = $authUser;
      } else {
        $user = $authUser;
        // todo require either fields
        $request->validate([
          'customer_id'     => 'int',
          'customer_email'  => 'email',
        ]);
        $customer = $user->findCustomer($request->customer_id, $request->customer_email);
        // auth user can attach properties for his customer service
        // $this->authorize('attach', $prop);
      }

      $customerProperties = $customer->properties()->createMany($creates);

      // ($customerProperties && $attachments) && $customerProperty->saveAttachments($attachments, 'attachments', true);
      return $customerProperties;
      // ->withAttachedUrl('attachments');
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
