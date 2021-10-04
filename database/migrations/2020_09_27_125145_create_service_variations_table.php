<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceVariationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('service_variations', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('service_id')->unsigned();
      $table->bigInteger('variation_id')->unsigned()->nullable();
      $table->string('value', 100)->nullable();
      $table->decimal('amount', 10,2)->nullable();
      $table->timestamps();
      $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
      $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('service_variations');
  }
}
