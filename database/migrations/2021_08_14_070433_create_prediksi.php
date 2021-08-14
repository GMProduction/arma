<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrediksi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prediksi', function (Blueprint $table) {
            $table->id();
            $table->integer('minggu');
            $table->bigInteger('barang_id')->unsigned();
            $table->integer('prediksi');
            $table->integer('kesalahan');
            $table->integer('masuk');
            $table->foreign('barang_id')->references('id')->on('barang');
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
        Schema::dropIfExists('prediksi');
    }
}
