<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsCopy1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions_copy1s', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->longText('explain')->nullable();
            $table->longText('choices');
            $table->longText('correct_choices');
            $table->unsignedBigInteger('quiz1_id');
            $table->foreign('quiz1_id')->references('id')->on('quiz_copy1s');
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
        Schema::dropIfExists('questions_copy1s');
    }
}
