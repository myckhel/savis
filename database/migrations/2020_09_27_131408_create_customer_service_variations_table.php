<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerServiceVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('customer_service_variations', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->bigInteger('customer_service_id')->unsigned();
        $table->bigInteger('service_variation_id')->unsigned();
        $table->timestamps();
        $table->foreign('customer_service_id')->references('id')->on('customer_services')->onDelete('cascade');
        $table->foreign('service_variation_id')->references('id')->on('service_variations')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_service_variations');
    }
}
