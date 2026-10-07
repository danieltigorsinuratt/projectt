<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Document;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->get('tahun', '2026');
        $search = $request->get('q');

        // Available years
        $yearsList = Folder::select('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->toArray();

        if (empty($yearsList)) {
            $yearsList = ['2026', '2025', '2024'];
        }

        // Count per year
        $yearCounts = [];
        foreach ($yearsList as $yr) {
            $yearCounts[$yr] = Document::where('tahun', $yr)->count();
        }

        // Folders for selected year
        $foldersQuery = Folder::withCount(['documents' => function ($query) use ($selectedYear) {
            $query->where('tahun', $selectedYear);
        }])
        ->where('tahun', $selectedYear);

        if ($search) {
            $foldersQuery->where('nama_folder', 'like', "%{$search}%");
        }

        $folders = $foldersQuery->get();

        return view('archives.index', compact('selectedYear', 'yearsList', 'yearCounts', 'folders', 'search'));
    }

    public function folder(Folder $folder, Request $request)
    {
        $search = $request->get('q');
        $selectedYear = $request->get('tahun', $folder->tahun ?? '2026');

        $query = Document::with(['folder', 'uploader'])
            ->where('folder_id', $folder->id);

        if ($selectedYear) {
            $query->where('tahun', $selectedYear);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul_dokumen', 'like', "%{$search}%")
                  ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $documents = $query->orderBy('nomor_dokumen', 'asc')->paginate(10)->withQueryString();

        return view('archives.folder', compact('folder', 'documents', 'selectedYear', 'search'));
    }

    public function create(Request $request)
    {
        $preselectedFolderId = $request->get('folder_id');
        $preselectedYear = $request->get('tahun', '2026');
        $folders = Folder::orderBy('nama_folder')->get();
        $years = ['2026', '2025', '2024', '2023', '2022'];

        return view('archives.create', compact('folders', 'years', 'preselectedFolderId', 'preselectedYear'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'nomor_dokumen' => 'nullable|string|max:100',
            'tanggal_dokumen' => 'required|date',
            'tahun' => 'required|string|max:20',
            'folder_id' => 'required|exists:folders,id',
            'keterangan' => 'nullable|string',
            'file_scan' => 'required|file|mimes:pdf,jpg,jpeg,png,docx,xlsx|max:20480', // max 20MB
        ]);

        $file = $request->file('file_scan');
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $file->getClientOriginalName());
        $path = $file->storeAs('documents', $filename, 'public');

        $document = Document::create([
            'folder_id' => $request->folder_id,
            'nomor_dokumen' => $request->nomor_dokumen,
            'judul_dokumen' => $request->nama_dokumen,
            'kategori' => Folder::find($request->folder_id)?->nama_folder,
            'tahun' => $request->tahun,
            'tanggal_dokumen' => $request->tanggal_dokumen,
            'deskripsi' => $request->keterangan,
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'file_type' => strtolower($file->getClientOriginalExtension()),
            'uploaded_by' => Auth::id(),
        ]);

        ActivityLog::log(
            'Upload Dokumen',
            "Mengunggah arsip {$document->judul_dokumen} ke folder " . ($document->folder?->nama_folder ?? '-')
        );

        return redirect()->route('archives.folder', [
            'folder' => $document->folder_id,
            'tahun' => $document->tahun
        ])->with('success', 'Dokumen arsip berhasil ditambahkan.');
    }

    public function show(Document $document)
    {
        $document->load(['folder', 'uploader']);

        ActivityLog::log(
            'Buka Dokumen',
            "Melihat detail & pratinjau dokumen {$document->judul_dokumen}"
        );

        return view('archives.show', compact('document'));
    }

    public function download(Document $document)
    {
        ActivityLog::log(
            'Unduh Dokumen',
            "Mengunduh file dokumen {$document->judul_dokumen}"
        );

        if (Storage::disk('public')->exists($document->file_path)) {
            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
            $cleanName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $document->judul_dokumen);
            if (!str_ends_with(strtolower($cleanName), '.' . $extension)) {
                $cleanName .= '.' . $extension;
            }
            return Storage::disk('public')->download($document->file_path, $cleanName);
        }

        return back()->with('error', 'File fisik tidak ditemukan pada penyimpanan.');
    }

    public function destroy(Document $document)
    {
        $judul = $document->judul_dokumen;
        $folderId = $document->folder_id;
        $tahun = $document->tahun;

        if (Storage::disk('public')->exists($document->file_path) && $document->file_path !== 'documents/sample_document.pdf') {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        ActivityLog::log(
            'Hapus Dokumen',
            "Menghapus arsip dokumen {$judul}"
        );

        return redirect()->route('archives.folder', ['folder' => $folderId, 'tahun' => $tahun])
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $results = [];
        if (strlen($query) > 0) {
            $results = Document::with(['folder', 'uploader'])
                ->where(function ($q) use ($query) {
                    $q->where('judul_dokumen', 'like', "%{$query}%")
                      ->orWhere('nomor_dokumen', 'like', "%{$query}%")
                      ->orWhere('tahun', 'like', "%{$query}%")
                      ->orWhere('deskripsi', 'like', "%{$query}%")
                      ->orWhereHas('folder', function ($fq) use ($query) {
                          $fq->where('nama_folder', 'like', "%{$query}%");
                      });
                })
                ->latest()
                ->take(15)
                ->get();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'results' => $results->map(function ($doc) {
                    return [
                        'id' => $doc->id,
                        'judul' => $doc->judul_dokumen,
                        'nomor' => $doc->nomor_dokumen,
                        'folder' => $doc->folder?->nama_folder ?? '-',
                        'tahun' => $doc->tahun,
                        'tanggal' => $doc->tanggal_dokumen ? $doc->tanggal_dokumen->format('d/m/Y') : '-',
                        'type' => strtoupper($doc->file_type),
                        'show_url' => route('archives.show', $doc->id),
                        'download_url' => route('archives.download', $doc->id),
                    ];
                })
            ]);
        }

        return view('archives.search', compact('query', 'results'));
    }
}
