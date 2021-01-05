<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()    
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('permissions')->insert([
            [ 'title' => 'CRUD administrators' ],
            [ 'title' => 'CRUD teachers' ],
            [ 'title' => 'CRUD students' ],
            [ 'title' => 'CRUD classrooms' ],
            [ 'title' => 'CRUD courses' ],
            [ 'title' => 'CRUD lessons' ],
            [ 'title' => 'CRUD topics' ],
            [ 'title' => 'CRUD sections' ],
            [ 'title' => 'CRUD assigned courses' ],
            [ 'title' => 'Student assigned courses' ],
            [ 'title' => 'CRUD exams' ],
            [ 'title' => 'CRUD schedules' ],
            [ 'title' => 'CRUD exams teachers' ],
            [ 'title' => 'Student exams' ],
            [ 'title' => 'CRUD events' ],
            [ 'title' => 'View events' ],
            [ 'title' => 'Leave applications' ],
            [ 'title' => 'Student applications' ],
            [ 'title' => 'CRUD behaviours' ],
            [ 'title' => 'View behaviours' ],
            [ 'title' => 'CRUD behaviour types' ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
