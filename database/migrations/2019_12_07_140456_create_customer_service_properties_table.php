<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerServicePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_service_properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_service_id')->unsigned();
            $table->bigInteger('customer_property_id')->unsigned();
            $table->bigInteger('service_property_id')->unsigned();
            // $table->json('properties');
            $table->timestamps();
        });


        Schema::table('customer_service_properties', function (Blueprint $table) {
          $table->foreign('customer_service_id')->references('id')->on('customer_services')->onDelete('cascade');
          $table->foreign('service_property_id')->references('id')->on('service_properties')->onDelete('cascade');
          $table->foreign('customer_property_id')->references('id')->on('customer_properties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_service_properties');
    }
}
