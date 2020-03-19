<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Customer;

class Work extends Model
{
  public static function countCompletedCustomerService(Customer $customer){
    $customer_services = $customer->customer_services->pluck('id');
    return self::where('status', 'completed')->whereIn('customer_service_id', $customer_services)->count();
  }

  public function customer_service(){
    return $this->belongsTo(CustomerService::class);
  }
}
