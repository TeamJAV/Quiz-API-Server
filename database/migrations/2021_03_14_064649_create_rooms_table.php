<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('status', 1);
            $table->string('required_name', 1);
            $table->string('is_shuffle', 1);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('quiz1_id')->nullable();
            $table->unsignedBigInteger('quiz2_id')->nullable();
            $table->foreign('quiz1_id')->references('id')->on('quiz_copy1s');
            $table->foreign('quiz2_id')->references('id')->on('quiz_copy2s');
            $table->foreign('user_id')->references('id')->on('users');
            $table->softDeletes();
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
        Schema::dropIfExists('rooms');
    }
}
