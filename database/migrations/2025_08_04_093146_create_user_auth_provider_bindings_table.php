<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAuthProviderBindingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_auth_provider_bindings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('auth_provider_id')->constrained('auth_providers')->onDelete('cascade'); // e.g., 'google', 'github', 'bintan_sso'
            $table->string('auth_provider_user_id');   // ID dari provider (misal: sub / uid)
            $table->string('access_token')->nullable(); // enkripsi jika disimpan
            $table->string('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
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
        Schema::dropIfExists('user_social_account_bindings');
    }
}
