<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitialDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->string('ID_User')->unique();
            $table->string('Email')->unique();
            $table->string('Password');
            $table->string('Nama');
            $table->string('No_Telp');
            $table->integer('Status');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        
        Schema::create('seller', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->string('ID_Seller')->unique();
            $table->string('ID_User');
            $table->string('Nama')->unique();
            $table->string('Alamat');
            $table->string('Kota');
            $table->string('Provinsi');
            $table->string('Kode_Pos');
            $table->string('No_Telp');
            $table->integer('Status');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
        
        Schema::create('product', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->string('ID_Product')->unique();
            $table->string('ID_Seller');
            $table->string('Nama');
            $table->longText('Deskripsi');
            $table->integer('Stok');
            $table->integer('Harga');
            $table->integer('ID_Satuan');
            $table->integer('Status');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });

        Schema::create('variant', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->string('ID_Variant')->unique();
            $table->string('ID_Product');
            $table->string('Nama');
            $table->integer('Stok');
            $table->integer('Harga');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
        Schema::dropIfExists('seller');
        Schema::dropIfExists('product');
        Schema::dropIfExists('variant');
    }
}
