<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpnameBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opname_barang', function (Blueprint $table) {
            $table->id('id_opname');
            $table->unsignedBigInteger('detail_barang_id');
            $table->date('tanggal_opname');
            $table->enum('jenis_opname', ['masuk', 'keluar', 'rusak']);
            $table->integer('jumlah_opname');
            $table->text('no_bukti');
            $table->text('keterangan');
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
        Schema::dropIfExists('opname_barang');
    }
}
