<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('customer_services', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->bigInteger('customer_id')->nullable()->unsigned();
        $table->bigInteger('service_id')->nullable()->unsigned();
        $table->timestamps();
      });
      Schema::table('customer_services', function (Blueprint $table) {
        $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_services');
    }
}
