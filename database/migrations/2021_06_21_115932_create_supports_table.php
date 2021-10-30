<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('supports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('business_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('conversation_id')->nullable()->constrained()->onDelete('set null');
        $table->string('title', 70);
        $table->enum('type', ['Bug', 'Feature Request', 'How To'])->default('Bug');
        $table->foreignId('closer_id')->nullable()->constrained('users')->onDelete('set null');
        $table->enum('status', ['Pending', 'Resolved', 'Reviewing'])->default('Pending');
        $table->timestampTz('closed_at')->nullable();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('supports');
    }
}
