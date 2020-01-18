<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Customer;

class Payment extends Model
{
  //
  public static function countCompletedCustomerService(Customer $customer){
    $customer_services = $customer->customer_services->pluck('id');
    return self::where('status', 'completed')->whereIn('customer_services_id', $customer_services)->count();
  }

  public function customer_service(){
    return $this->belongsTo(CustomerService::class);
  }
}
