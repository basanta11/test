<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestQuestionOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_question_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('test_question_id');
            $table->text('title');
            $table->string('type')->default(0)->comment('0: Text, 1: Image');
            $table->boolean('is_correct')->default(0)->comment('0: False, 1: True');
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
        Schema::dropIfExists('test_question_options');
    }
}
