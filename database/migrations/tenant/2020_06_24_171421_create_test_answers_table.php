<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( !Schema::hasTable('test_answers') ) {
            Schema::create('test_answers', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('test_set_user_id');
                $table->unsignedInteger('test_question_id');
                $table->unsignedInteger('test_question_option_id')->nullable();
                $table->longText('answer')->nullable();
                $table->float('marks',5 ,2)->nullable();
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
        Schema::dropIfExists('test_answers');
    }
}
