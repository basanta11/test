<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('set_id');
            $table->unsignedInteger('user_id');
            $table->tinyInteger('is_finished')->default(0)->comment('0: False, 1: True');
            $table->tinyInteger('teacher_checking')->default(1)->comment('1: Not Checked, 2: Checking, 3: Done');
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
        Schema::dropIfExists('set_user');
    }
}
