<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'aksi',
        'keterangan',
        'ip_address',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function log(string $aksi, ?string $keterangan = null, ?int $userId = null): self
    {
        return self::create([
            'user_id' => $userId ?? auth()->id() ?? 1,
            'aksi' => $aksi,
            'keterangan' => $keterangan,
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}
