<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumenPerbaikanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumen_perbaikan', function (Blueprint $table) {
            $table->id('id_dok_perbaikan');
            $table->integer('perbaikan_id');
            $table->text('file_perbaikan');
            $table->date('tanggal_dokumen');
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
        Schema::dropIfExists('dokumen_perbaikan');
    }
}
