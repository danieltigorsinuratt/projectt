<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Folder;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArchiveSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');

        $loginResponse = $this->get('/login');
        $loginResponse->assertStatus(200);
        $loginResponse->assertSee('Sistem Pengarsipan Dokumen');
        $loginResponse->assertSee('Divisi Jaringan Konstruksi');
    }

    public function test_user_can_login_with_username_or_email(): void
    {
        $admin = User::where('username', 'admin')->first();

        $response = $this->post('/login', [
            'login' => 'admin',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_authenticated_user_can_view_dashboard_and_archives(): void
    {
        $admin = User::where('username', 'admin')->first();

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Total Arsip');
        $response->assertSee('Arsip Terbaru');

        $archiveResponse = $this->actingAs($admin)->get('/arsip');
        $archiveResponse->assertStatus(200);
        $archiveResponse->assertSee('Folder Tahun');
    }

    public function test_can_view_folder_documents_and_document_detail(): void
    {
        $admin = User::where('username', 'admin')->first();
        $folder = Folder::first();

        $folderResponse = $this->actingAs($admin)->get("/arsip/folder/{$folder->id}");
        $folderResponse->assertStatus(200);

        $document = Document::create([
            'folder_id' => $folder->id,
            'nomor_dokumen' => '001/TEST/2026',
            'judul_dokumen' => 'Dokumen Pengujian',
            'tahun' => '2026',
            'tanggal_dokumen' => '2026-01-10',
            'file_path' => 'documents/test.pdf',
            'file_size' => 1024,
            'file_type' => 'pdf',
            'uploaded_by' => $admin->id,
        ]);

        $docResponse = $this->actingAs($admin)->get("/dokumen/{$document->id}");
        $docResponse->assertStatus(200);
        $docResponse->assertSee('Informasi Dokumen');
        $docResponse->assertSee('Dokumen Pengujian');
    }

    public function test_upload_and_download_document(): void
    {
        Storage::fake('public');
        $petugas = User::where('username', 'budi')->first();
        $folder = Folder::first();

        $file = UploadedFile::fake()->create('test_arsip.pdf', 500, 'application/pdf');

        $response = $this->actingAs($petugas)->post('/arsip/upload', [
            'nama_dokumen' => 'Dokumen Uji Sistem',
            'nomor_dokumen' => '999/TEST/2026',
            'tanggal_dokumen' => '2026-03-01',
            'tahun' => '2026',
            'folder_id' => $folder->id,
            'keterangan' => 'Uji coba upload',
            'file_scan' => $file,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('documents', [
            'nomor_dokumen' => '999/TEST/2026',
            'judul_dokumen' => 'Dokumen Uji Sistem',
        ]);
    }
}
