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
         $table->string('access_code')->unique()->nullable();
         $table->string('reference')->nullable();
         $table->decimal('amount', 9,3);
         $table->enum('type', ['card', 'cash', 'pos'])->default('card');
         $table->enum('status', ['processing', 'success', 'on hold', 'pending', 'completed', 'canceled', 'failed'])->default('pending');
         $table->string('message')->nullable();
         $table->string('authorization_code')->nullable();
         $table->string('currency_code')->nullable();
         $table->timestamp('paid_at')->nullable();
         $table->timestamps();
       });

       Schema::table('payments', function (Blueprint $table) {
         $table->foreign('customer_service_id')->references('id')->on('customer_services')->onDelete('cascade');
       });
       DB::statement("ALTER TABLE payments AUTO_INCREMENT = 345226;");
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
