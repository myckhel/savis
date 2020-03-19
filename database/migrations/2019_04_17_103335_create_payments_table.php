<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
       Schema::create('payments', function (Blueprint $table) {
         $table->bigIncrements('id');
         $table->bigInteger('customer_service_id')->unsigned();
         $table->float('paid', 10, 2)->nullable();
         $table->enum('status', ['processing', 'on hold', 'pending', 'completed', 'canceled', 'failed'])->default('pending');
         $table->string('reference')->nullable();
         $table->string('authorization_code')->nullable();
         $table->string('currency_code')->nullable();
         $table->timestamp('payed_at')->nullable();
         $table->timestamps();
       });

       Schema::table('payments', function (Blueprint $table) {
         $table->foreign('customer_service_id')->references('id')->on('customer_services')->onDelete('cascade');
       });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::dropIfExists('payments');
     }
}
