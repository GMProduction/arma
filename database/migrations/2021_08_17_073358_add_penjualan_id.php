<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPenjualanId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prediksi', function (Blueprint $table) {
            //
            $table->bigInteger('penjualan_id')->unsigned()->unique();
            $table->foreign('penjualan_id')->references('id')->on('penjualan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prediksi', function (Blueprint $table) {
            //
        });
    }
}
