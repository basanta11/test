<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToFrontendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('frontends', function (Blueprint $table) {
            if (!Schema::hasColumn('frontends', 'card_color')) {
                $table->string('card_color')->nullable();
            }
            if (!Schema::hasColumn('frontends', 'map')) {
                $table->text('map')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('frontends', function (Blueprint $table) {
            //
            $table->dropColumn('card_color');
            $table->dropColumn('map');
        });
    }
}
