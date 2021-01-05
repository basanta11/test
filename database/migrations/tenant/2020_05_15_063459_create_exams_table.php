<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->comment('Teacher ID');
            $table->unsignedInteger('course_id');
            $table->string('title');
            $table->dateTime('exam_start');
            $table->integer('duration');
            $table->tinyInteger('type')->default(0)->comment('0: 1st Term, 1: 2nd Term, 2: 3rd Term');
            $table->integer('full_marks');
            $table->integer('pass_marks');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('exams');
    }
}
