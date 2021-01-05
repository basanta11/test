<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lesson_id');
            $table->string('title');
            $table->dateTime('test_start');
            $table->integer('duration');
            $table->tinyInteger('type')->default(0)->comment('0: Pre, 1: Post');
            $table->integer('full_marks');
            $table->integer('pass_marks');
            $table->boolean('status')->default(0);
            $table->integer('created_by');
            // $table->tinyInteger('show_result')->default(0);
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
        Schema::dropIfExists('tests');
    }
}
