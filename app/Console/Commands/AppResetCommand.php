<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;
use Illuminate\Support\Facades\Log;

class AppResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset';

    /**
     * The console command description.
     *
     * @var string
     */
     protected $description = 'Reset the app by refreshing db, force reintsall passport and ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      Log::debug("app:reset");
      print("app:reset\n");
      $output['freshDb'] = Artisan::call('migrate:fresh');
      print("freshDb");
      $output['passportDb'] = Artisan::call('migrate', [
        '--path' => 'vendor/laravel/passport/database/migrations', '--force' => true
      ]);
      print("passportDb\n");
      $output['passportInstall'] = Artisan::call('passport:install');
      print("passportInstall\n");
      $output['eventGen'] = Artisan::call('event:generate');
      print("eventGen\n");
      $output['dbSeed'] = Artisan::call('db:seed');
      print("dbSeed\n");
      // $output['MigrateDB'] = Artisan::call('migrate');
      Log::debug($output);
      print_r($output);
    }
}
