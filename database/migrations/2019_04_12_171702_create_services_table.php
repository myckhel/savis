<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('services', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->bigInteger('business_id')->unsigned();
        $table->bigInteger('service_id')->unsigned()->nullable();
        $table->string('name', 100);
        $table->float('price', 10, 2)->nullable();
        $table->string('charge', 100)->nullable();
        $table->softDeletes();
        $table->timestamps();
      });//

     Schema::table('services', function (Blueprint $table) {
       $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
       $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
     });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
