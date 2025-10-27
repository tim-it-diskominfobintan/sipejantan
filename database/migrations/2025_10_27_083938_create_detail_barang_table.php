<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_barang', function (Blueprint $table) {
            $table->id('id_detail_barang');
            $table->integer('barang_id');
            $table->string('kode_barang');
            $table->date('tanggal_mulai_garansi');
            $table->integer('lama_garansi');
            $table->date('tanggal_masuk');
            $table->text('keterangan_barang');
            $table->text('foto_detail_barang');
            $table->timestamps();
            $table->text('created_by')->nullable();
            $table->text('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_barang');
    }
}
