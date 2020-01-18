<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->bigInteger('service_property_id')->unsigned()->nullable();
            $table->string('value');
            $table->timestamps();
        });
        Schema::table('customer_properties', function (Blueprint $table) {
          $table->foreign('service_property_id')->references('id')->on('service_properties')->onDelete('cascade');
          $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_properties');
    }
}
