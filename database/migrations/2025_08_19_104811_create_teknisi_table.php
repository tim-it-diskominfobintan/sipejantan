<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeknisiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_teknisi', function (Blueprint $table) {
            $table->id('id_teknisi');
            $table->string('nik_teknisi');
            $table->string('nama_teknisi');
            $table->string('email_teknisi');
            $table->string('hp_teknisi');
            $table->unsignedBigInteger('kelurahan_id');
            $table->unsignedBigInteger('kecamatan_id');
            $table->text('alamat_teknisi');
            $table->unsignedBigInteger('penanggung_jawab_id');
            $table->text('foto_teknisi');
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
        Schema::dropIfExists('m_teknisi');
    }
}
