<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'folder_id',
        'nomor_dokumen',
        'judul_dokumen',
        'kategori',
        'tahun',
        'tanggal_dokumen',
        'deskripsi',
        'file_path',
        'file_size',
        'file_type',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_dokumen' => 'date',
            'file_size' => 'integer',
        ];
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }
        return $bytes . ' B';
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }
}
