<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Observers\CustomerServiceObserver;
use App\Models\CustomerService;
use ChatSystem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      CustomerService::observe(CustomerServiceObserver::class);
      ChatSystem::registerObservers();
      // Validator::extend('exists', ModelExist::class);
    }
}
