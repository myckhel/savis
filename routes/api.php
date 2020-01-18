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
Route::group([ 'prefix' => 'auth' ], function () {
  Route::post('login', 'AuthController@login');
  Route::post('signup', 'AuthController@signup');
  Route::get('signup/activate/{token}', 'AuthController@signupActivate');

  Route::group([ 'middleware' => 'auth:api' ], function() {
    Route::get('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@user');
  });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([ 'middleware' => 'auth:api' ], function() {
  Route::get('users/current', 'UserController@current');
  Route::delete('customers/delete/multiple', 'CustomerController@delete');
  // Route::get('customer_services', 'CustomerController@customer_services');
  Route::delete('services/delete/multiple', 'ServiceController@delete');

  // Route::group(['middleware' => 'can:view,App\Customer'], function() {
  Route::get('customers/profile/{customer}', 'CustomerController@profile');
  Route::get('customers/payments/{customer}', 'CustomerController@payments');
  Route::get('customers/jobs/{customer}', 'CustomerController@jobs');
  Route::get('customers/properties/{customer}', 'CustomerController@properties');
  Route::delete('users/customers/{customer}', 'UserController@destroyCustomer');
  Route::post('users/customers/{customer}', 'UserController@addCustomer');
  Route::get('users/stats', 'UserController@stats');
  // });
  Route::resource('customers', 'CustomerController');
  Route::resource('customer_services', 'CustomerServiceController');
  Route::resource('service-metas', 'ServiceMetaController');
  Route::resource('users', 'UserController');
  Route::resource('services', 'ServiceController');
  Route::resource('payments', 'PaymentController');

});
