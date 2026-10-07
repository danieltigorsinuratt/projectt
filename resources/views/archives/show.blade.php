@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
        <a href="{{ route('archives.index') }}" class="hover:text-slate-600">Arsip</a>
        <span>&gt;</span>
        <a href="{{ route('archives.index', ['tahun' => $document->tahun]) }}" class="hover:text-slate-600">{{ $document->tahun }}</a>
        <span>&gt;</span>
        <a href="{{ route('archives.folder', ['folder' => $document->folder_id, 'tahun' => $document->tahun]) }}" class="hover:text-slate-600">
            {{ $document->folder?->nama_folder ?? 'Folder' }}
        </a>
    </div>

    <!-- Title -->
    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
        {{ $document->judul_dokumen }}
    </h1>

    <!-- SPLIT VIEW: PDF VIEWER (LEFT) + METADATA CARD (RIGHT) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT: DOCUMENT VIEWER (7 or 8 cols) -->
        <div class="lg:col-span-8 bg-[#2d3238] rounded-3xl overflow-hidden shadow-lg border border-slate-700 flex flex-col">
            
            <!-- Toolbar -->
            <div class="bg-[#212529] px-5 py-3 flex items-center justify-between text-slate-300 text-xs border-b border-slate-700 select-none">
                <!-- Left tools -->
                <div class="flex items-center gap-3">
                    <button type="button" class="p-1.5 hover:text-white transition">
                        <i class="fa-solid fa-table-columns"></i>
                    </button>
                    <button type="button" class="p-1.5 hover:text-white transition">
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                    <button type="button" class="p-1.5 hover:text-white transition">
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>

                <!-- Center Zoom -->
                <div class="flex items-center gap-3 bg-[#2d3238] px-3 py-1 rounded-lg border border-slate-700">
                    <button type="button" onclick="adjustZoom(-10)" class="hover:text-white">-</button>
                    <span id="zoomLevel" class="font-mono text-slate-200">100%</span>
                    <button type="button" onclick="adjustZoom(10)" class="hover:text-white">+</button>
                </div>

                <!-- Right tools -->
                <div class="flex items-center gap-3">
                    <button type="button" onclick="rotateDoc()" class="p-1.5 hover:text-white transition">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                    <button type="button" onclick="toggleFullScreen()" class="p-1.5 hover:text-white transition">
                        <i class="fa-solid fa-expand"></i>
                    </button>
                </div>
            </div>

            <!-- Document Viewport -->
            <div id="docViewerContainer" class="p-6 md:p-10 flex items-center justify-center overflow-auto bg-[#383d44] min-h-[600px] max-h-[750px]">
                
                @if(strtolower($document->file_type) === 'pdf')
                    <!-- Simulated Document Page Rendering matching PLN Mockup -->
                    <div id="documentSheet" class="w-full max-w-[550px] bg-white text-slate-800 rounded-lg shadow-2xl p-8 sm:p-10 transition-transform duration-200 origin-top text-xs leading-relaxed min-h-[720px] flex flex-col justify-between">
                        <div>
                            <!-- Header PLN -->
                            <div class="flex items-center justify-between border-b-2 border-slate-800 pb-4 mb-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 bg-[#fac000] rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M13 2L3 14h7v8l11-13h-8l0-7z"/>
                                        </svg>
                                    </div>
                                    <span class="font-extrabold text-base tracking-wider">PLN</span>
                                </div>
                                <span class="text-[10px] text-slate-400">Hal. 1 dari 1</span>
                            </div>

                            <!-- Document Heading -->
                            <div class="text-center my-6">
                                <h3 class="font-bold text-sm uppercase tracking-wider text-slate-900">BERITA ACARA</h3>
                                <p class="text-[11px] text-slate-500 mt-0.5">Nomor: {{ $document->nomor_dokumen ?: '001/TUG9/2026' }}</p>
                            </div>

                            <p class="text-slate-600 mb-4 text-[11px]">
                                Pada hari ini, telah dilaksanakan penyerahan berkas arsip digital jaringan konstruksi PT PLN (Persero) dengan rincian dokumen sebagai berikut:
                            </p>

                            <!-- Document Metadata Summary inside sheet -->
                            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 mb-5 space-y-1.5 text-[10px]">
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Kategori:</span>
                                    <span class="font-semibold text-slate-800">{{ $document->kategori ?: ($document->folder?->nama_folder ?? '-') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Periode / Tahun:</span>
                                    <span class="font-semibold text-slate-800">{{ $document->tahun }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Unit Kerjasama:</span>
                                    <span class="font-semibold text-slate-800">Divisi Jaringan Konstruksi</span>
                                </div>
                            </div>

                            <!-- Lines placeholder -->
                            <div class="space-y-2 mb-6 opacity-60">
                                <div class="h-2 bg-slate-200 rounded-full w-full"></div>
                                <div class="h-2 bg-slate-200 rounded-full w-11/12"></div>
                                <div class="h-2 bg-slate-200 rounded-full w-4/5"></div>
                                <div class="h-2 bg-slate-200 rounded-full w-9/12"></div>
                            </div>

                            @if($document->deskripsi)
                                <div class="text-slate-600 text-[11px] italic bg-amber-50/50 p-3 rounded border border-amber-100 mb-6">
                                    "{{ $document->deskripsi }}"
                                </div>
                            @endif
                        </div>

                        <!-- Signatures -->
                        <div class="grid grid-cols-2 text-center text-[10px] text-slate-500 pt-8 border-t border-slate-100">
                            <div>
                                <p class="mb-12">Yang Menyerahkan</p>
                                <p class="font-bold text-slate-800">( {{ $document->uploader?->nama_lengkap ?? 'Petugas Arsip' }} )</p>
                            </div>
                            <div>
                                <p class="mb-12">Yang Menerima</p>
                                <p class="font-bold text-slate-800">( Admin PLN )</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Image / other format viewer -->
                    <div class="text-center p-8 bg-white rounded-2xl shadow-xl max-w-md">
                        <i class="fa-regular fa-file-lines text-5xl text-sky-500 mb-3 block"></i>
                        <h4 class="text-base font-bold text-slate-800">{{ $document->judul_dokumen }}</h4>
                        <p class="text-xs text-slate-400 mt-1">Format: {{ strtoupper($document->file_type) }} &bull; Ukuran: {{ $document->formatted_size }}</p>
                    </div>
                @endif

            </div>
        </div>

        <!-- RIGHT: INFORMASI DOKUMEN & ACTION BUTTONS (4 or 5 cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Metadata Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-xs border border-slate-100/90">
                <h3 class="text-base font-extrabold text-slate-900 mb-6">Informasi Dokumen</h3>
                
                <div class="space-y-4 text-xs">
                    <div class="flex items-start justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Nama Dokumen</span>
                        <span class="font-bold text-slate-800 text-right max-w-[60%]">{{ $document->judul_dokumen }}</span>
                    </div>

                    <div class="flex items-start justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Nomor Dokumen</span>
                        <span class="font-semibold text-slate-800 text-right">{{ $document->nomor_dokumen ?: '-' }}</span>
                    </div>

                    <div class="flex items-start justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Tanggal Dokumen</span>
                        <span class="font-semibold text-slate-800 text-right">
                            {{ $document->tanggal_dokumen ? $document->tanggal_dokumen->isoFormat('D MMMM Y') : '-' }}
                        </span>
                    </div>

                    <div class="flex items-start justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Tahun</span>
                        <span class="font-semibold text-slate-800 text-right">{{ $document->tahun }}</span>
                    </div>

                    <div class="flex items-start justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Folder</span>
                        <span class="font-semibold text-slate-800 text-right">{{ $document->folder?->nama_folder ?? '-' }}</span>
                    </div>

                    <div class="flex items-start justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Uploader</span>
                        <span class="font-semibold text-slate-800 text-right">{{ $document->uploader?->nama_lengkap ?? 'Petugas Arsip' }}</span>
                    </div>

                    <div class="flex items-start justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Tanggal Upload</span>
                        <span class="font-semibold text-slate-800 text-right">{{ $document->created_at->isoFormat('D MMMM Y') }}</span>
                    </div>

                    <div class="flex items-start justify-between py-1">
                        <span class="text-slate-400 font-medium">Keterangan</span>
                        <span class="font-normal text-slate-600 text-right max-w-[60%]">{{ $document->deskripsi ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Action Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{ route('archives.download', $document->id) }}" 
                   class="flex-1 py-3.5 px-4 bg-[#0072db] hover:bg-[#005fb8] text-white font-bold text-sm rounded-2xl shadow-sm text-center transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-download"></i>
                    <span>Download</span>
                </a>

                <a href="{{ route('archives.folder', ['folder' => $document->folder_id, 'tahun' => $document->tahun]) }}" 
                   class="py-3.5 px-6 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-bold text-sm rounded-2xl transition text-center">
                    Kembali
                </a>
            </div>

        </div>

    </div>

</div>

<script>
    let currentZoom = 100;
    let currentRotation = 0;

    function adjustZoom(delta) {
        currentZoom = Math.max(50, Math.min(200, currentZoom + delta));
        document.getElementById('zoomLevel').innerText = currentZoom + '%';
        applyDocTransform();
    }

    function rotateDoc() {
        currentRotation = (currentRotation + 90) % 360;
        applyDocTransform();
    }

    function applyDocTransform() {
        const sheet = document.getElementById('documentSheet');
        if (sheet) {
            sheet.style.transform = `scale(${currentZoom / 100}) rotate(${currentRotation}deg)`;
        }
    }

    function toggleFullScreen() {
        const viewer = document.getElementById('docViewerContainer');
        if (!document.fullscreenElement) {
            viewer.requestFullscreen().catch(err => {
                alert(`Error entering fullscreen: ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    }
</script>
@endsection
