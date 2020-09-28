<?php

use Illuminate\Http\Request;

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
  Route::get('users/count', fn () => App\User::count());

  Route::group([ 'prefix' => 'auth' ], function () {
    // user auth
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');
    // customer auth
    Route::post('signin', 'AuthController@signin');
    Route::post('register', 'AuthController@register');
    Route::get('register/activate/{token}', 'AuthController@registrActivate');

    Route::group([ 'middleware' => 'auth:api' ], function() {
      Route::get('logout', 'AuthController@logout');
      Route::get('user', 'AuthController@user');
    });
    Route::group([ 'middleware' => 'auth:customer' ], function() {
      Route::get('signout', 'AuthController@signout');
    });
  });

  Route::middleware('auth:api')->get('/user', function (Request $request) {
      return $request->user();
  });

  Route::group([ 'middleware' => 'auth:customer' ], function() {
    Route::post('customers/metas/add', 'CustomerController@addMeta');
    Route::get('customers/metas/get', 'CustomerController@getMeta');
    Route::post('customers/payments/verify',            'PaymentController@verify');
    Route::resource('users', 'UserController')->only(['update', 'index']);
    Route::resource('customers/payments',             'PaymentController');
    Route::resource('customers', 'CustomerController')->only(['update']);
    Route::group([ 'prefix' => 'customers'], function() {
      Route::apiResources([
        'customer_services'               => 'CustomerServiceController',
        'medias'                          => 'MediaController',
        'jobs'                            => 'WorkController',
        'properties'                      => 'CustomerPropertyController',
        'services'                        => 'CustomerServiceController',
        'metas'                           => 'MetaController',
        'service_properties'              => 'CustomerServicePropertyController',
        'payments'                        => 'PaymentController',
      ]);
    });
    // Route::post('users/customers/{customer}', 'UserController@addCustomer');
  });

  Route::group([ 'middleware' => 'auth:api' ], function() {
    Route::get('users/current', 'UserController@current');
    Route::delete('customers/delete/multiple', 'CustomerController@delete');
    // Route::get('customer_services', 'CustomerController@customer_services');
    Route::delete('services', 'ServiceController@delete');

    // Route::group(['middleware' => 'can:view,App\Customer'], function() {
    Route::get('customers/profile/{customer}', 'CustomerController@profile');
    Route::get('customers/payments/{customer}', 'CustomerController@payments');
    Route::get('customers/jobs/{customer}', 'CustomerController@jobs');
    Route::get('customers/properties/{customer}', 'CustomerController@properties');
    Route::delete('users/customers/{customer}', 'UserController@destroyCustomer');
    Route::post('users/customers/{customer}', 'UserController@addCustomer');
    Route::get('users/stats', 'UserController@stats');
    // });
    Route::get('services/{service}/fields', 'ServiceController@fields');

    Route::resource('customers', 'CustomerController')->except(['update']);
    Route::resource('users', 'UserController')->except(['index']);
    Route::apiResources([
      'customer_services'           => 'CustomerServiceController',
      'service_properties'          => 'ServicePropertyController',
      'customer_service_properties' => 'CustomerServicePropertyController',
      'services'                    => 'ServiceController',
      'payments'                    => 'PaymentController',
      'metas'                       => 'MetaController',
      'jobs'                        => 'WorkController',
      'customer_properties'         => 'CustomerPropertyController',
      'medias'                      => 'MediaController',
      'variations'                  => 'VariationController',
    ]);
  });
});
