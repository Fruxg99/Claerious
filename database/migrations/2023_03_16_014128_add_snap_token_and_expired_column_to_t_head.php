<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSnapTokenAndExpiredColumnToTHead extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_heads', function (Blueprint $table) {
            $table->string('snap_token')->after('total');
            $table->timestamp('expired')->after('snap_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_heads', function (Blueprint $table) {
            $table->dropColumn('snap_token');
            $table->dropColumn('expired');
        });
    }
}
