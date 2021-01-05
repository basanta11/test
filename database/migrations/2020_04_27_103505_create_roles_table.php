<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        DB::table('roles')->insert([
            [ 'title' => 'Principal', 'created_at' => date('Y-m-d H:i:s') ],
            [ 'title' => 'Administrator', 'created_at' => date('Y-m-d H:i:s') ],
            [ 'title' => 'Teacher', 'created_at' => date('Y-m-d H:i:s') ],
            [ 'title' => 'Student', 'created_at' => date('Y-m-d H:i:s') ],
            [ 'title' => 'Guardian', 'created_at' => date('Y-m-d H:i:s') ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
