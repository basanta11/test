<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('course_id')->nullable();
            $table->unsignedInteger('section_id');
            $table->string('day');
            $table->tinyInteger('type')->default(0)->comment('0: break, 1: teaching');
            $table->time('start_time');
            $table->time('end_time');
            $table->tinyInteger('status')->default(0)->comment('0: inactive, 1:active');
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
        Schema::dropIfExists('schedules');
    }
}
