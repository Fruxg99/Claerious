<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIdVariantColumnFromTDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_details', function (Blueprint $table) {
            $table->dropColumn('id_variant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_details', function (Blueprint $table) {
            $table->string('id_variant')->nullable()->after('id_product');
        });
    }
}
