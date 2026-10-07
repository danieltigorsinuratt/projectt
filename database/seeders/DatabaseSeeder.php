<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Folder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::firstOrCreate(['nama_role' => 'Admin']);
        $petugasRole = Role::firstOrCreate(['nama_role' => 'Petugas Arsip']);
        $pegawaiRole = Role::firstOrCreate(['nama_role' => 'Pegawai']);

        // 2. Users
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'role_id' => $adminRole->id,
                'nama_lengkap' => 'Admin',
                'email' => 'admin@pln.co.id',
                'password' => Hash::make('password'),
                'nip_nik' => '198501012010011001',
                'status' => 'Aktif',
            ]
        );

        $budi = User::firstOrCreate(
            ['username' => 'budi'],
            [
                'role_id' => $petugasRole->id,
                'nama_lengkap' => 'Budi Santoso',
                'email' => 'budi@pln.co.id',
                'password' => Hash::make('password'),
                'nip_nik' => '198904122015021002',
                'status' => 'Aktif',
            ]
        );

        $siti = User::firstOrCreate(
            ['username' => 'siti'],
            [
                'role_id' => $petugasRole->id,
                'nama_lengkap' => 'Siti Aminah',
                'email' => 'siti@pln.co.id',
                'password' => Hash::make('password'),
                'nip_nik' => '199208202018012003',
                'status' => 'Aktif',
            ]
        );

        $rizky = User::firstOrCreate(
            ['username' => 'rizky'],
            [
                'role_id' => $pegawaiRole->id,
                'nama_lengkap' => 'Rizky Pratama',
                'email' => 'rizky@pln.co.id',
                'password' => Hash::make('password'),
                'nip_nik' => '199503152020031004',
                'status' => 'Aktif',
            ]
        );

        $andi = User::firstOrCreate(
            ['username' => 'andi'],
            [
                'role_id' => $pegawaiRole->id,
                'nama_lengkap' => 'Andi Saputra',
                'email' => 'andi@pln.co.id',
                'password' => Hash::make('password'),
                'nip_nik' => '199607102021021005',
                'status' => 'Aktif',
            ]
        );

        // Ensure storage directory exists
        Storage::disk('public')->makeDirectory('documents');

        // 3. Folders
        $years = ['2026', '2025', '2024'];
        $folderNames = [
            'TUG 3' => 'Folder dokumen konstruksi dan transmisi TUG 3',
            'TUG 4' => 'Folder dokumen konstruksi dan transmisi TUG 4',
            'TUG 9' => 'Folder dokumen konstruksi dan transmisi TUG 9',
            'TUG 10' => 'Folder dokumen konstruksi dan transmisi TUG 10',
            'Berita Acara (BA)' => 'Berita Acara serah terima dan pemeriksaan arsip',
            'Lainnya' => 'Dokumen pendukung dan arsip lain',
        ];

        foreach ($years as $yr) {
            foreach ($folderNames as $name => $desc) {
                Folder::firstOrCreate(
                    [
                        'nama_folder' => $name,
                        'tahun' => $yr,
                    ],
                    [
                        'deskripsi' => $desc,
                        'created_by' => $admin->id,
                    ]
                );
            }
        }

        // 4. Initial System Activity Log
        ActivityLog::create([
            'user_id' => $admin->id,
            'aksi' => 'Inisialisasi Sistem',
            'keterangan' => 'Sistem pengarsipan dokumen digital PLN siap digunakan',
            'ip_address' => '127.0.0.1',
            'created_at' => now(),
        ]);
    }
}
