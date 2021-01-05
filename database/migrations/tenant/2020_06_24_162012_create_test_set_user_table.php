<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestSetUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('test_set_user') ) {
            Schema::create('test_set_user', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('test_set_id');
                $table->unsignedInteger('user_id');
                $table->tinyInteger('is_finished')->default(0)->comment('0: False, 1: True');
                $table->tinyInteger('teacher_checking')->default(1)->comment('1: Not Checked, 2: Checking, 3: Done');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_set_user');
    }
}
