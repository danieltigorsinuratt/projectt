<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function index()
    {
        // Group or list unique folders or folders with their years
        $folders = Folder::withCount('documents')
            ->orderBy('nama_folder')
            ->get();

        return view('folders.index', compact('folders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_folder' => 'required|string|max:150',
            'tahun' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        $folder = Folder::create([
            'nama_folder' => $request->nama_folder,
            'tahun' => $request->tahun,
            'deskripsi' => $request->deskripsi,
            'created_by' => Auth::id(),
        ]);

        ActivityLog::log('Tambah Folder', "Menambahkan folder baru: {$folder->nama_folder} ({$folder->tahun})");

        return redirect()->route('folders.index')->with('success', 'Folder berhasil ditambahkan.');
    }

    public function update(Request $request, Folder $folder)
    {
        $request->validate([
            'nama_folder' => 'required|string|max:150',
            'tahun' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
        ]);

        $oldName = $folder->nama_folder;
        $folder->update([
            'nama_folder' => $request->nama_folder,
            'tahun' => $request->tahun,
            'deskripsi' => $request->deskripsi,
        ]);

        ActivityLog::log('Edit Folder', "Mengubah data folder dari {$oldName} menjadi {$folder->nama_folder}");

        return redirect()->route('folders.index')->with('success', 'Folder berhasil diperbarui.');
    }

    public function destroy(Folder $folder)
    {
        $name = $folder->nama_folder;
        $folder->delete();

        ActivityLog::log('Hapus Folder', "Menghapus folder {$name}");

        return redirect()->route('folders.index')->with('success', 'Folder berhasil dihapus.');
    }
}
