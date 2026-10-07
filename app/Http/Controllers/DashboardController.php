<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Document;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalArsip = Document::count();
        $totalFolder = Folder::distinct('nama_folder')->count('nama_folder');
        $totalUser = User::count();
        $activeYear = date('Y') <= 2026 ? '2026' : date('Y');

        $uploadHariIni = Document::whereDate('created_at', today())->count();

        // Recent documents
        $arsipTerbaru = Document::with(['folder', 'uploader'])
            ->latest('id')
            ->take(6)
            ->get();

        // Recent activities
        $aktivitasTerbaru = ActivityLog::with('user')
            ->latest('id')
            ->take(6)
            ->get();

        // Popular folders (folders with most documents)
        $popularFolders = Folder::withCount('documents')
            ->where('tahun', $activeYear)
            ->orderByDesc('documents_count')
            ->take(4)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'totalArsip',
            'totalFolder',
            'totalUser',
            'activeYear',
            'uploadHariIni',
            'arsipTerbaru',
            'aktivitasTerbaru',
            'popularFolders'
        ));
    }
}
