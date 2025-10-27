<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->string('no_laporan');
            $table->unsignedBigInteger('pelapor_id');
            $table->unsignedBigInteger('asset_id');
            $table->text('deskripsi_laporan');
            $table->enum('status_laporan', ['diterima', 'pending', 'proses', 'selesai','ditolak']);
            $table->date('tanggal_laporan');
            $table->text('foto_laporan');
            $table->unsignedBigInteger('teknisi_id');
            $table->text('ket_tolak');
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
        Schema::dropIfExists('laporan');
    }
}
