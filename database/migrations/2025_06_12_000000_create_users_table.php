<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('photo_profile')->nullable();
            $table->enum('status', ['active', 'inactive', 'locked', 'pending_verification', 'register_incomplete'])->default('active');
            $table->string('status_description')->nullable();
            $table->enum('auth_provider', ['self', 'bintan-sso', 'google', 'facebook'])->default('self');
            $table->string('auth_provider_user_id', 36)->nullable()->comment('ini gunanya untuk simpan id asli dari usernya, misal google account id, bintan sso id, facebook id');
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->string('last_login_device')->nullable();
            $table->string('last_device')->nullable();
            $table->timestamp('last_activity_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
