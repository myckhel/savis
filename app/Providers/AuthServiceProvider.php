<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use ChatSystem;
use App\Policies\{
    BusinessPolicy,
    CustomerPolicy,
    CustomerPropertyPolicy,
    CustomerServicePolicy,
    CustomerServiceVariationPolicy,
    PaymentPolicy,
    ServicePolicy,
    ServicePropertyPolicy,
    ServiceVariationPolicy,
    UserPolicy,
    VariationPolicy,
    WorkerPolicy,
    WorkPolicy,
    SupportPolicy,
};
use App\Models\{
    Business,
    Customer,
    CustomerProperty,
    CustomerService,
    CustomerServiceVariation,
    Payment,
    Service,
    ServiceProperty,
    ServiceVariation,
    User,
    Variation,
    Worker,
    Work,
    Support,
};

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
      Business::class                   => BusinessPolicy::class,
      Customer::class                   => CustomerPolicy::class,
      CustomerProperty::class           => CustomerPropertyPolicy::class,
      CustomerService::class            => CustomerServicePolicy::class,
      CustomerServiceVariation::class   => CustomerServiceVariationPolicy::class,
      Payment::class                    => PaymentPolicy::class,
      Service::class                    => ServicePolicy::class,
      ServiceProperty::class            => ServicePropertyPolicy::class,
      ServiceVariation::class           => ServiceVariationPolicy::class,
      User::class                       => UserPolicy::class,
      Variation::class                  => VariationPolicy::class,
      Worker::class                     => WorkerPolicy::class,
      Work::class                       => WorkPolicy::class,
      Support::class                    => SupportPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        ChatSystem::registerPolicies();
        $this->registerPolicies();
    }
}
