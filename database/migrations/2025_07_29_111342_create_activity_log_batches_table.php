<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log_batches', function (Blueprint $table) {
            $table->id();
            $table->uuid('batch_uuid')->unique(); // UUID unik untuk batch
            $table->timestamp('latest_log_at');     // waktu log terakhir dalam batch
            $table->unsignedInteger('log_count');   // total log dalam batch
            $table->timestamps();                   // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_log_batches');
    }
}
