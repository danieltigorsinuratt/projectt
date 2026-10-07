<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'nama_lengkap',
        'email',
        'username',
        'password',
        'nip_nik',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class, 'created_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return strtolower($this->role?->nama_role ?? '') === 'admin';
    }

    public function isPetugas(): bool
    {
        return strtolower($this->role?->nama_role ?? '') === 'petugas arsip';
    }

    public function isPegawai(): bool
    {
        return strtolower($this->role?->nama_role ?? '') === 'pegawai';
    }

    public function getRoleDisplayNameAttribute(): string
    {
        if ($this->isAdmin()) {
            return 'Administrator';
        }
        return $this->role?->nama_role ?? 'Pengguna';
    }
}
