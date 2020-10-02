<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Customer;

class Payment extends Model
{
  protected $fillable = ['customer_service_id', 'access_code', 'type', 'reference', 'amount', 'status', 'message', 'authorization_code', 'currency_code', 'paid_at'];
  //
  public static function countCompletedCustomerService(Customer $customer){
    $customer_services = $customer->customer_services->pluck('id');
    return self::where('status', 'completed')->whereIn('customer_services_id', $customer_services)->count();
  }

  public function customer_service(){
    return $this->belongsTo(CustomerService::class);
  }
}
