<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public $timestamps = true;
    protected $table = "users";
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $appends = [
        'status_online',
        'photo_profile_url',
        'joined_at',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    private $online_min = 120; // 2 menit (120 detik)

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = trim($value);
    }

    public function getStatusOnlineAttribute()
    {
        return $this->last_activity_at && (time() - strtotime($this->last_activity_at)) < $this->online_min
            ? 'online'
            : 'offline';
    }

    public function getPhotoProfileUrlAttribute()
    {
        return $this->photo_profile != null ||  $this->photo_profile != '' ? asset('storage/' . $this->photo_profile) : asset('assets/global/img/default-avatar.jpg');
    }

    public function getJoinedAtAttribute()
    {
        return $this->created_at
            ? $this->created_at->toDateTimeString()
            : null;
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginAttempt::class);
    }

    // semua assignment OPD (bisa lebih dari satu)
    public function assignedOpd()
    {
        return $this->hasMany(AssignUsersOpd::class);
    }

    // hanya yang aktif
    public function assignedAktifOpd()
    {
        return $this->hasMany(AssignUsersOpd::class)->where('status', 'aktif');
    }

    public function auth_provider_bindings()
    {
        return $this->belongsToMany(AuthProvider::class, 'user_auth_provider_bindings', 'user_id', 'auth_provider_id')
            ->withPivot('created_at', 'updated_at');
    }

    public function admin()
    {
        return $this->hasOne(ProfilAdmin::class, 'user_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'model_has_roles', 'model_id', 'role_id');
    }

    public function opd()
    {
        return $this->belongsToMany(Opd::class, 'assign_users_opd', 'user_id', 'opd_id')
            ->withPivot('created_at', 'updated_at');
    }

    // langsung ambil model Opd yang aktif
    public function opdAktif()
    {
        return $this->opd()
            ->withPivot('status')
            ->wherePivot('status', 'aktif')
            ->withTimestamps();
    }

    public function profil_admin()
    {
        return $this->hasOne(ProfilAdmin::class, 'user_id', 'id');
    }

    public function profil_pegawai()
    {
        return $this->hasOne(ProfilPegawai::class, 'user_id', 'id');
    }
}
