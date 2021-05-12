<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionCopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_copies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('explain')->nullable();
            $table->longText('choices');
            $table->longText('correct_choices');
            $table->unsignedBigInteger('quiz_copy_id');
            $table->foreign('quiz_copy_id')->references('id')->on('quiz_copies');
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
        Schema::dropIfExists('question_copies');
    }
}
