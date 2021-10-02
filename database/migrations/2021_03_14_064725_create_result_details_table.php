<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_details', function (Blueprint $table) {
            $table->id();
            $table->string('student_name', 50)->nullable();
            $table->unsignedBigInteger('scores');
            $table->longText('student_choices')->nullable();
            $table->timestamp('time_joined')->useCurrent();
            $table->timestamp('time_end')->nullable();
            $table->integer('room_pending_id')->nullable();
            $table->longText('timestamp_out')->nullable();
            $table->tinyInteger('is_finished')->default(0);
            $table->unsignedBigInteger('result_id');
            $table->foreign('result_id')->references('id')->on('result_tests');
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
        Schema::dropIfExists('result_details');
    }
}
