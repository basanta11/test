<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('test_set_id');
            $table->longText('title');
            $table->tinyInteger('type')->default(0)->comment('0: Upload PDF, 1: Single Choice, 2: Multi Choice, 3: Image Upload, 4: Paragraph, 5: Text');
            $table->text('note')->nullable();
            $table->integer('order');
            $table->float('marks', 5, 2);
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
        Schema::dropIfExists('test_questions');
    }
}
