<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_providers', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();           // misal: 'google', 'github', 'bintan_sso'
            $table->string('name');             // 'Google', 'GitHub', dsb
            $table->string('logo')->nullable();
            $table->string('logo_dark')->nullable();
            $table->string('type');                     // 'oauth2', 'oidc', 'saml', dll (optional)
            $table->string('is_enabled')->default(true);
            $table->json('config')->nullable();         // kalau kamu simpan settings per provider
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
        Schema::dropIfExists('auth_providers');
    }
}
