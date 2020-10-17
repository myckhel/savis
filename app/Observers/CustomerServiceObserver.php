<?php

namespace App\Observers;

use App\Models\CustomerService;

class CustomerServiceObserver
{
    /**
     * Handle the customer service "created" event.
     *
     * @param  \App\Models\CustomerService  $customerService
     * @return void
     */
    public function created(CustomerService $customerService)
    {
      $customerService->job()->create();
    }

    /**
     * Handle the customer service "updated" event.
     *
     * @param  \App\Models\CustomerService  $customerService
     * @return void
     */
    public function updated(CustomerService $customerService)
    {
        //
    }

    /**
     * Handle the customer service "deleted" event.
     *
     * @param  \App\Models\CustomerService  $customerService
     * @return void
     */
    public function deleted(CustomerService $customerService)
    {
        //
    }

    /**
     * Handle the customer service "restored" event.
     *
     * @param  \App\Models\CustomerService  $customerService
     * @return void
     */
    public function restored(CustomerService $customerService)
    {
        //
    }

    /**
     * Handle the customer service "force deleted" event.
     *
     * @param  \App\Models\CustomerService  $customerService
     * @return void
     */
    public function forceDeleted(CustomerService $customerService)
    {
        //
    }
}
