<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('role_id');
            $table->string('email')->unique();
            $table->string('citizen_number')->nullable();
            $table->string('symbol_number')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('gender')->comment('0: male, 1: female, 2: others');
            $table->tinyInteger('status')->comment('0: invited,1 : active, 2: inactive')->default(0);
            $table->string('phone')->nullable();
            $table->string('house_number')->nullable();
            $table->string('address')->nullable();
            $table->string('school_name')->nullable();
            $table->string('password');
            $table->text('image')->nullable();
            $table->text('image_url')->nullable();
            $table->integer('created_by')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
