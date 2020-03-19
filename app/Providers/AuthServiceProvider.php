<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
      // 'App\Model' => 'App\Policies\ModelPolicy',
      // 'App\Customer' => 'App\Policies\CustomerPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        Passport::routes();
        passport::$revokeOtherTokens;
        passport::$pruneRevokedTokens;
        // Passport::tokensExpireIn(Carbon::now()->addDays(1));
        // Passport::refreshTokensExpireIn(Carbon::now()->addDays(2));
    }
}
