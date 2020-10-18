<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
  use HasFactory;
  protected $fillable = ['customer_service_id', 'access_code', 'type', 'reference', 'amount', 'status', 'message', 'authorization_code', 'currency_code', 'paid_at'];
  //
  public function scopeBusiness($q, $business_id, $vars = []){
    $user_id = $vars['user_id'] ?? null;
    $q->whereHas('customerService',
      fn ($q) => $q->whereHas('service',
        fn ($q) => $q->whereHas('business',
          fn ($q) => $q->whereId($business_id)
          ->when($user_id,
            fn ($q) => $q->whereHas('workers',
              fn ($q) => $q->whereUserId($user_id)
            )
          )
        )
      )
    );
  }
  public static function countCompletedCustomerService(Customer $customer){
    $customer_services = $customer->customer_services->pluck('id');
    return self::where('status', 'completed')->whereIn('customer_services_id', $customer_services)->count();
  }

  public function customerService(){
    return $this->belongsTo(CustomerService::class);
  }
}
