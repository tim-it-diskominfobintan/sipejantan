<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerbaikanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perbaikan', function (Blueprint $table) {
            $table->id('id_perbaikan');
            $table->unsignedBigInteger('laporan_id');
            $table->enum('status_perbaikan', ['mandiri', 'umum']);
            $table->date('tanggal_perbaikan');
            $table->text('foto_perbaikan')->nullable();
            $table->text('keterangan');
            $table->enum('status_progress', ['proses', 'selesai', 'ditolak']);
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
        Schema::dropIfExists('perbaikan');
    }
}
