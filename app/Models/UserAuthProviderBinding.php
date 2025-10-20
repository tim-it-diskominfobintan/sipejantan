<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class UserAuthProviderBinding extends Model
{
    use HasFactory;

    protected $table = 'user_auth_provider_bindings';
    protected $guarded = [];
    protected $casts = [
        'token_expires_at' => 'datetime',
        // 'access_token' => 'encrypted',
        // 'refresh_token' => 'encrypted',
    ];


    public function getAccessTokenAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            // fallback: kalau gagal dekripsi, bisa log atau kembalikan null
            return null;
        }
    }

    public function setAccessTokenAttribute($value)
    {
        if ($value === null) {
            $this->attributes['access_token'] = null;
        } else {
            $this->attributes['access_token'] = Crypt::encryptString($value);
        }
    }

    public function getRefreshTokenAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function setRefreshTokenAttribute($value)
    {
        if ($value === null) {
            $this->attributes['refresh_token'] = null;
        } else {
            $this->attributes['refresh_token'] = Crypt::encryptString($value);
        }
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke auth provider
    public function authProvider()
    {
        return $this->belongsTo(AuthProvider::class, 'auth_provider_id');
    }

    // Helper: cek apakah token belum kadaluarsa
    public function tokenValid(): bool
    {
        if (! $this->token_expires_at) {
            return true;
        }
        return $this->token_expires_at->isFuture();
    }

    // Optional helper update tokens
    public function updateTokens(string $accessToken, ?string $refreshToken = null, ?\DateTimeInterface $expiresAt = null): void
    {
        $this->access_token = $accessToken;
        $this->refresh_token = $refreshToken;
        $this->token_expires_at = $expiresAt;
        $this->save();
    }
}
