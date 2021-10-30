<?php

use Illuminate\Http\Request;
use App\Http\Controllers\{
    BusinessController,
    WorkerController,
    AuthController,
    WorkController,
    UserController,
    MediaController,
    PaymentController,
    ServiceController,
    CustomerController,
    VariationController,
    CustomerServiceController,
    ServicePropertyController,
    ServiceVariationController,
    CustomerPropertyController,
    CustomerServicePropertyController,
    CustomerServiceVariationController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([ 'middleware' => 'localization' ], function () {
  Route::get('users/count', fn () => App\Models\User::count());

  Route::group([ 'prefix' => 'auth' ], function () {
    // user auth
    Route::post('login',                    [AuthController::class, 'login']);
    Route::post('signup',                   [AuthController::class, 'signup']);
    Route::get('signup/activate/{token}',   [AuthController::class, 'signupActivate']);
    // customer auth
    Route::post('signin',                   [AuthController::class, 'signin']);
    Route::post('register',                 [AuthController::class, 'register']);
    Route::get('register/activate/{token}', [AuthController::class, 'registrActivate']);

    Route::group([ 'middleware' => 'auth:api' ], function() {
      Route::get('logout',                  [AuthController::class, 'logout']);
      Route::get('user',                    [AuthController::class, 'user']);
    });
    Route::group([ 'middleware' => 'auth:customer' ], function() {
      Route::get('signout',                 [AuthController::class, 'signout']);
    });
  });

  Route::middleware('auth:api')->get('/user', function (Request $request) {
      return $request->user();
  });

  /*Route::group([ 'middleware' => 'auth:customer' ], function() {
    Route::post('customers/payments/verify',          [PaymentController::class, 'verify']);
    Route::resource('users', UserController::class)->only(['update', 'index']);
    Route::resource('customers/payments',             PaymentController::class);
    Route::group([ 'prefix' => 'customers'], function() {
      Route::apiResources([
        'customer_services'               => CustomerServiceController::class,
        'medias'                          => MediaController::class,
        'jobs'                            => WorkController::class,
        'properties'                      => CustomerPropertyController::class,
        'services'                        => CustomerServiceController::class,
        'service_properties'              => CustomerServicePropertyController::class,
        'payments'                        => PaymentController::class,
      ]);
    });
    // Route::post('users/customers/{customer}', 'UserController@addCustomer');
});*/

  Route::group([ 'middleware' => 'auth:api' ], function() {
    Route::get('whoami',                       [UserController::class, 'whoami']);
    Route::get('users/current',                [UserController::class, 'current']);
    Route::delete('customers/delete/multiple', [CustomerController::class, 'delete']);
    // Route::get('customer_services', [CustomerController::class, 'customer_services']);
    Route::delete('services',                  [ServiceController::class, 'delete']);

    // Route::group(['middleware' => 'can:view,App\Customer'], function() {
    Route::get('customers/profile/{customer}',    [CustomerController::class, 'profile']);
    Route::get('customers/payments/{customer}',   [CustomerController::class, 'payments']);
    Route::get('customers/jobs/{customer}',       [CustomerController::class, 'jobs']);
    Route::get('customers/properties/{customer}', [CustomerController::class, 'properties']);
    Route::delete('users/customers/{customer}',   [UserController::class, 'destroyCustomer']);
    Route::post('users/customers/{customer}',     [UserController::class, 'addCustomer']);
    Route::get('users/stats',                     [UserController::class, 'stats']);
    // });
    Route::get('services/{service}/fields',       [ServiceController::class, 'fields']);
    Route::post('payments/verify',                [PaymentController::class, 'verify']);

    Route::resource('customers',    CustomerController::class);
    Route::resource('users',        UserController::class);
    // Route::resource('service_variations', ServiceVariationController::class)->only(['index', 'show']);
    Route::apiResources([
      'customer_services'           => CustomerServiceController::class,
      'service_properties'          => ServicePropertyController::class,
      'customer_service_properties' => CustomerServicePropertyController::class,
      'services'                    => ServiceController::class,
      'payments'                    => PaymentController::class,
      'jobs'                        => WorkController::class,
      'customer_properties'         => CustomerPropertyController::class,
      'medias'                      => MediaController::class,
      'variations'                  => VariationController::class,
      'service_variations'          => ServiceVariationController::class,
      'customer_service_variations' => CustomerServiceVariationController::class,
      'business'                    => BusinessController::class,
      'workers'                     => WorkerController::class,
    ]);
  });
});
