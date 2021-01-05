<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HomeworkUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('homework_user', function (Blueprint $table) {
            $table->id();
            $table->integer('homework_id');
            $table->integer('user_id');
            $table->text('answer');
            $table->float('obtained_marks')->nullable();
            $table->tinyInteger('status')->default(0);
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
        //
        Schema::table('homework_user', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
