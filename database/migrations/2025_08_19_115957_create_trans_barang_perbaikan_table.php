<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransBarangPerbaikanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_barang_perbaikan', function (Blueprint $table) {
            $table->id('id_trans_barang_perbaikan');
            $table->unsignedBigInteger('perbaikan_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('jumlah_barang');
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
        Schema::dropIfExists('trans_barang_perbaikan');
    }
}
