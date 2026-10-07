<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('folders')->cascadeOnDelete();
            $table->string('nama_folder', 150);
            $table->string('tahun', 50)->default('2026');
            $table->text('deskripsi')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->constrained('folders')->cascadeOnDelete();
            $table->string('nomor_dokumen', 100)->nullable();
            $table->string('judul_dokumen', 255);
            $table->string('kategori', 100)->nullable();
            $table->string('tahun', 20)->default('2026');
            $table->date('tanggal_dokumen')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('file_path', 255);
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('file_type', 50)->default('pdf');
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('aksi', 100);
            $table->text('keterangan')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('folders');
    }
};
