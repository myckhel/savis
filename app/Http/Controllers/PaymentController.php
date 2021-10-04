<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\CustomerService;
use Paystack;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->validate([
        'search'        => '',
        'orderBy'       => ['regex:(amount|message|reference|currency_code|status)', 'nullable'],
        'pageSize'      => 'int',
        'business_id'   => 'int',
      ]);

      $user           = $request->user();
      $search         = $request->search;
      $orderBy        = $request->orderBy;
      $pageSize       = $request->pageSize;
      $business_id    = $request->business_id;

      return Payment::business($business_id, ['user_id' => $user->id])
      ->when($search,
        fn ($q) => $q->where('status', 'LIKE', '%'.$search.'%')
        ->orWhere('amount', 'LIKE', '%'.$search.'%')->orWhere('reference', 'LIKE', '%'.$search.'%')
        ->orWhere('message', 'LIKE', '%'.$search.'%')->orWhere('currency_code', 'LIKE', '%'.$search.'%')
      )->orderBy($orderBy ?? 'id')
      ->paginate($pageSize ?? 15);
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
        'customer_service_id'   => 'required|int',
        'business_id'           => 'int',
        'type'                  => 'in:card,cash,pos',
        'status'                => 'in:pending,completed,failed',
      ]);
      \Request::instance()->query->set('trxref', $request->trxref);
      $type                = $request->type;
      $status              = $request->status;
      $business_id         = $request->business_id;
      $customer_service_id = $request->customer_service_id;
      $user                = $request->user();

      if ($business_id) {
        $business = $user->findOrFailBusinessWhereHas(
          $request->business_id,
          ['customer_service_id' => $customer_service_id],
        );
        $amount = CustomerService::find($customer_service_id)->getAmount();

        return Payment::create([
          'customer_service_id'   => $customer_service_id,
          'amount'                => $amount,
          'type'                  => $type,
          'status'                => $status,
        ]);
      } else {
        $customerService  = CustomerService::findOrFail($request->customer_service_id);
        $data             = ["amount" => $amount, "email" => $user->email];
        $response         = Paystack::getAuthorizationResponse($data);

        return $user->payments()->create([
          'customer_service_id'   => $request->customer_service_id,
          'amount'                => $amount,
          'access_code'           => $response['access_code'],
          'reference'             => $request->trxref,
        ]);
      }
    }

    public function verify(Request $request)
    {
      $request->validate([/*'reference' => 'required',*/ 'trxref' => 'required']);
      $paymentDetails   = Paystack::getPaymentData();
      // dd($paymentDetails);
      $payment          = Payment::where('reference', $paymentDetails->reference)->first();

      if ($payment && $payment->status == 'pending') {
        $user           = $payment->user;
        if ($paymentDetails->status != 'success') {
          $payment->update([
            'status' => $paymentDetails->status,
          ]);
        }

        if ($paymentDetails->status == 'success') {
          $payment->update([
            'status'              => $paymentDetails->status,
            'message'             => $paymentDetails->message,
            'reference'           => $paymentDetails->reference,
            'authorization_code'  => $paymentDetails->authorization['authorization_code'],
            'currency_code'       => $paymentDetails->currency,
            'paid'                => now(),//$paymentDetails['data']['paidAt'],
          ]);
          // $user->deposit($paymentDetails->amount);
          // if ($paymentDetails->status = "success" && $paymentDetails->authorization['reusable']) {
          //   $user->payment_options()->firstOrCreate(
          //     ['signature' => $paymentDetails->authorization['signature']],
          //     $paymentDetails->authorization
          //   );
          // }
        }

        return ['status' => true, 'payment' => $payment];
      }
      return ['status' => false, 'payment' => $payment];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
      return $payment;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
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
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
