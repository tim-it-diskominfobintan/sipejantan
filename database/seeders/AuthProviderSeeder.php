<?php

namespace Database\Seeders;

use App\Models\AuthProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthProviderSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $providers = [
            [
                'slug' => 'google',
                'name' => 'Google',
                'logo' => '/images/auth/google.svg',
                'logo_dark' => '/images/auth/google-dark.svg',
                'type' => 'oidc',
                'is_enabled' => false,
                'config' => json_encode([
                    'discovery_url' => 'https://accounts.google.com/.well-known/openid-configuration',
                    'scope' => ['openid', 'profile', 'email'],
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'github',
                'name' => 'GitHub',
                'logo' => '/images/auth/github.svg',
                'logo_dark' => '/images/auth/github-dark.svg',
                'type' => 'oauth2',
                'is_enabled' => false,
                'config' => json_encode([
                    'authorize_url' => 'https://github.com/login/oauth/authorize',
                    'token_url' => 'https://github.com/login/oauth/access_token',
                    'scope' => ['user:email'],
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'facebook',
                'name' => 'Facebook',
                'logo' => '/images/auth/facebook.svg',
                'logo_dark' => '/images/auth/facebook-dark.svg',
                'type' => 'oauth2',
                'is_enabled' => false,
                'config' => json_encode([
                    'authorize_url' => 'https://www.facebook.com/v15.0/dialog/oauth',
                    'token_url' => 'https://graph.facebook.com/v15.0/oauth/access_token',
                    'scope' => ['email', 'public_profile'],
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'bintan-sso',
                'name' => 'Bintan SSO',
                'logo' => '/images/auth/bintan-sso.svg',
                'logo_dark' => '/images/auth/bintan-sso-dark.svg',
                'type' => 'oauth2', // atau 'oidc' tergantung implementasinya
                'is_enabled' => true,
                'config' => json_encode([]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        AuthProvider::upsert(
            $providers,
            ['slug'], // unique key untuk update jika sudah ada
            ['name', 'logo', 'logo_dark', 'type', 'is_enabled', 'config', 'updated_at']
        );
    }
}