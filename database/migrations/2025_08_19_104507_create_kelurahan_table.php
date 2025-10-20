<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelurahanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_kelurahan', function (Blueprint $table) {
            $table->id('id_kelurahan');
            $table->text('kode_kelurahan');
            $table->text('nama_kelurahan');
            $table->unsignedBigInteger('kecamatan_id');
            $table->timestamps();
            $table->text('created_by')->nullable();
            $table->text('updated_by')->nullable();

            $table->foreign('kecamatan_id')->references('id_kecamatan')->on('m_kecamatan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_kelurahan');
    }
}
