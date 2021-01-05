<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');
        });

        DB::table('permission_role')->insert([
            [ 'permission_id' => 1, 'role_id' => 1 ],
            [ 'permission_id' => 2, 'role_id' => 1 ],
            [ 'permission_id' => 3, 'role_id' => 1 ],
            [ 'permission_id' => 4, 'role_id' => 1 ],
            [ 'permission_id' => 5, 'role_id' => 1 ],
            [ 'permission_id' => 6, 'role_id' => 1 ],
            [ 'permission_id' => 7, 'role_id' => 1 ],
            [ 'permission_id' => 2, 'role_id' => 2 ],
            [ 'permission_id' => 3, 'role_id' => 2 ],
            [ 'permission_id' => 4, 'role_id' => 2 ],
            [ 'permission_id' => 5, 'role_id' => 2 ],
            [ 'permission_id' => 6, 'role_id' => 2 ],
            [ 'permission_id' => 7, 'role_id' => 2 ],
            [ 'permission_id' => 9, 'role_id' => 3 ],
            [ 'permission_id' => 6, 'role_id' => 3 ],
            [ 'permission_id' => 7, 'role_id' => 3 ],
            [ 'permission_id' => 8, 'role_id' => 1 ],
            [ 'permission_id' => 8, 'role_id' => 2 ],
            [ 'permission_id' => 10, 'role_id' => 4 ],
            [ 'permission_id' => 11, 'role_id' => 1 ],
            [ 'permission_id' => 11, 'role_id' => 2 ],
            [ 'permission_id' => 12, 'role_id' => 1 ],
            [ 'permission_id' => 12, 'role_id' => 2 ],
            [ 'permission_id' => 13, 'role_id' => 3 ],
            [ 'permission_id' => 14, 'role_id' => 4 ],
            [ 'permission_id' => 15, 'role_id' => 1 ],
            [ 'permission_id' => 15, 'role_id' => 2 ],
            [ 'permission_id' => 16, 'role_id' => 3 ],
            [ 'permission_id' => 16, 'role_id' => 4 ],
            [ 'permission_id' => 16, 'role_id' => 5 ],
            [ 'permission_id' => 17, 'role_id' => 3 ],
            [ 'permission_id' => 17, 'role_id' => 4 ],
            [ 'permission_id' => 18, 'role_id' => 4 ],
            [ 'permission_id' => 19, 'role_id' => 3 ],
            [ 'permission_id' => 20, 'role_id' => 5 ],
            [ 'permission_id' => 21, 'role_id' => 1 ],
            [ 'permission_id' => 21, 'role_id' => 2 ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_role');
    }
}
