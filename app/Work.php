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

  public function scopeSearch ($q, $search) {
    if ($search) {
      return $q->where(function ($q) use($search) {
        $q->where('status', $search);
      });
    }
    return $q;
  }

  protected $fillable = ['status'];

  public function customer_service(){
    return $this->belongsTo(CustomerService::class);
  }
}
