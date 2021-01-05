<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontends', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->text('banners')->nullable();
            $table->text('about_us')->nullable();
            $table->text('social_links')->nullable();
            $table->text('contacts')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();

            $table->string('mission')->nullable();
            $table->string('mission_image')->nullable();
            $table->string('vision')->nullable();
            $table->string('vision_image')->nullable();
            $table->string('goal')->nullable();
            $table->string('goal_image')->nullable();

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
        Schema::dropIfExists('frontends');
    }
}
