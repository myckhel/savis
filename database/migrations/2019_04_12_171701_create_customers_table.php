<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('customers', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('business_id')->unsigned();
      $table->bigInteger('user_id')->unsigned();
      $table->json('metas')->nullable(); //->default('{}');
      $table->timestamps();
      $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('customers');
  }
}
