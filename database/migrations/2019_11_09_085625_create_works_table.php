<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_service_id')->unsigned();
            $table->enum('status', ['processing', 'on hold', 'pending', 'completed', 'canceled', 'failed'])->default('pending');
            $table->timestamps();
        });

        Schema::table('works', function (Blueprint $table) {
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
        Schema::dropIfExists('works');
    }
}
