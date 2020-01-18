<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//shared server clear cache
Route::get('/clear-cache', function() {
    return Artisan::call('cache:clear');
    // return what you want
});

Route::get('/test', function(){
  return App\Service::all()->count();
});

// migrate db
Route::get('/db/migrate', function() {
    return Artisan::call('migrate');
});

Route::get('/app/reset', function() {
  $output = [];
  // $output['inspire'] = Artisan::call('inspire');
  $output['freshDb'] = Artisan::call('migrate:fresh');
  $output['passportDb'] = Artisan::call('migrate', [
    '--path' => 'vendor/laravel/passport/database/migrations', '--force' => true
  ]);
  // sleep(10);
  // $output['passportInstall'] = shell_exec('php ../artisan passport:install');
  $output['passportInstall'] = Artisan::call('passport:install');
  // sleep(3);
  $output['eventGen'] = Artisan::call('event:generate');
  $output['dbSeed'] = Artisan::call('db:seed');
  // sleep(3);
  return $output;
});

Route::get('/db/migrate/fresh', function() {
  return Artisan::call('migrate:fresh');
});

// Api::routes();

Route::any('{query}',
  function() { return view('welcome'); })
  ->where('query', '^(?!api).*$');
// Route::view('/login','welcome');
// Route::view('/register','welcome');
// Route::view('/', 'welcome');
// Route::view('home','welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
