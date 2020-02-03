<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNestedSetToService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
          $table->nestedSet();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
         //  $table->integer('parent_id')->unsigned()->nullable()->index();
         // $table->integer('left')->unsigned()->nullable()->index();
         // $table->integer('right')->unsgined()->nullable()->index();
         // $table->integer('depth')->unsigned()->nullable()->index();
        });
    }
}
