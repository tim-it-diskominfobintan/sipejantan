<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class BintanSsoTokenService
{
    protected string $tokenUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $clientScope;

    public function __construct()
    {
        $this->tokenUrl = config('bintan-sso-client.endpoint') . '/oauth/token';
        $this->clientId = config('bintan-sso-client.client_id');
        $this->clientSecret = config('bintan-sso-client.client_secret');
        $this->clientScope = config('bintan-sso-client.scopes');
    }

    /**
     * Ambil access token, gunakan cache jika masih berlaku
     */
    public function getAccessToken(): ?string
    {
        if ($this->isTokenValid()) {
            return Cache::get('bintan-sso.access_token');
        }

        return $this->refreshAccessToken();
    }

    /**
     * Cek apakah token masih valid berdasarkan exp
     */
    public function isTokenValid(): bool
    {
        return Cache::has('bintan-sso.access_token.expires_at') &&
               now()->lt(Cache::get('bintan-sso.access_token.expires_at'));
    }

    /**
     * Paksa ambil token baru dan simpan ke cache
     */
    public function refreshAccessToken(): ?string
    {
        $token = $this->requestNewToken();
        
        if ($token) {
            $expiry = $this->parseJwtExpiry($token);

            if ($expiry) {
                // Simpan token dan expired ke cache (buffer 60 detik agar aman)
                $ttl = $expiry->diffInSeconds(now()) - 60;
                Cache::put('bintan-sso.access_token', $token, $ttl);
                Cache::put('bintan-sso.access_token.expires_at', $expiry);
            } else {
                Log::warning('Gagal parsing expiry dari token SSO');
            }
        }

        return $token;
    }

    /**
     * Request token baru dari Bintan SSO
     */
    private function requestNewToken(): ?string
    {
        try {
            $response = Http::asForm()->post($this->tokenUrl, [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => $this->clientScope,
            ]);

            if ($response->successful()) {
                return $response->json()['access_token'] ?? null;
            }

            Log::error('Gagal ambil token dari Bintan SSO', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Exception saat ambil token SSO: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Decode payload JWT dan ambil exp timestamp
     */
    private function parseJwtExpiry(string $jwt): ?Carbon
    {
        $parts = explode('.', $jwt);

        if (count($parts) !== 3) {
            return null;
        }

        $payloadBase64 = strtr($parts[1], '-_', '+/');
        $payloadJson = base64_decode($payloadBase64);
        $payload = json_decode($payloadJson, true);

        if (!isset($payload['exp'])) {
            return null;
        }

        return Carbon::createFromTimestamp($payload['exp']);
    }

    public function deleteAccessToken()
    {
        Cache::forget('bintan-sso.access_token');
        Cache::forget('bintan-sso.access_token.expires_at');
    }
}
