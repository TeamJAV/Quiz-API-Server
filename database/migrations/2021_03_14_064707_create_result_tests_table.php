<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_tests', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0);
            $table->dateTime('date_create');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('quiz_copy_id');
            $table->foreign('room_id')->references('id')->on('rooms');
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
        Schema::dropIfExists('result_tests');
    }
}
