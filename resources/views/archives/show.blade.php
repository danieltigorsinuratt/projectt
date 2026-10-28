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
        
        <!-- LEFT: DOCUMENT VIEWER (8 cols) -->
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
                    <button type="button" onclick="adjustZoom(-1)" class="hover:text-white px-1">-</button>
                    <span id="zoomLevel" class="font-mono text-slate-200 min-w-[3rem] text-center">150%</span>
                    <button type="button" onclick="adjustZoom(1)" class="hover:text-white px-1">+</button>
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
            <div id="docViewerContainer" class="flex items-start justify-center overflow-auto bg-[#383d44] min-h-[600px] max-h-[750px] p-6">
                @php $fileType = strtolower($document->file_type); @endphp

                @if($fileType === 'pdf')
                    <!-- Loading indicator shown while PDF.js loads -->
                    <div id="pdfLoading" class="flex flex-col items-center justify-center gap-3 text-slate-300 mt-20 w-full">
                        <i class="fa-solid fa-spinner fa-spin text-3xl"></i>
                        <span class="text-sm">Memuat dokumen...</span>
                    </div>
                    <!-- Canvas where PDF.js renders the first page -->
                    <canvas id="pdfCanvas" class="shadow-2xl rounded hidden" style="max-width:100%;"></canvas>

                @elseif(in_array($fileType, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    <!-- Image viewer -->
                    <img id="imgViewer"
                         src="{{ $document->file_url }}"
                         alt="{{ $document->judul_dokumen }}"
                         class="shadow-2xl rounded transition-transform duration-200"
                         style="max-width:100%; max-height:700px; object-fit:contain; transform-origin: top center;">

                @else
                    <!-- Unsupported format fallback -->
                    <div class="flex flex-col items-center justify-center gap-4 text-center p-10 bg-white rounded-2xl shadow-xl max-w-md mx-auto mt-16">
                        <i class="fa-regular fa-file-lines text-5xl text-sky-500"></i>
                        <div>
                            <h4 class="text-base font-bold text-slate-800">{{ $document->judul_dokumen }}</h4>
                            <p class="text-xs text-slate-400 mt-1">Format: {{ strtoupper($document->file_type) }} &bull; Ukuran: {{ $document->formatted_size }}</p>
                            <p class="text-xs text-slate-500 mt-3">Pratinjau tidak tersedia. Gunakan tombol Download.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT: INFORMASI DOKUMEN & ACTION BUTTONS (4 cols) -->
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

@if(strtolower($document->file_type) === 'pdf')
{{-- PDF.js CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    let pdfDoc        = null;
    let currentScale  = 1.5;
    let currentRot    = 0;

    const canvas   = document.getElementById('pdfCanvas');
    const loading  = document.getElementById('pdfLoading');
    const ctx      = canvas.getContext('2d');
    const pdfUrl   = @json($document->file_url);

    function renderPage(page) {
        const vp = page.getViewport({ scale: currentScale, rotation: currentRot });
        canvas.width  = vp.width;
        canvas.height = vp.height;
        return page.render({ canvasContext: ctx, viewport: vp }).promise;
    }

    pdfjsLib.getDocument(pdfUrl).promise
        .then(pdf => {
            pdfDoc = pdf;
            return pdf.getPage(1);
        })
        .then(page => {
            loading.classList.add('hidden');
            canvas.classList.remove('hidden');
            renderPage(page);
        })
        .catch(err => {
            loading.innerHTML =
                '<i class="fa-solid fa-circle-exclamation text-rose-400 text-3xl"></i>' +
                '<span class="text-rose-300 text-sm">Gagal memuat PDF.</span>';
            console.error('PDF.js error:', err);
        });

    function adjustZoom(step) {
        // step: +1 or -1, each step = 10%
        currentScale = Math.max(0.5, Math.min(3.0, parseFloat((currentScale + step * 0.1).toFixed(1))));
        document.getElementById('zoomLevel').innerText = Math.round(currentScale * 100) + '%';
        if (pdfDoc) pdfDoc.getPage(1).then(p => renderPage(p));
    }

    function rotateDoc() {
        currentRot = (currentRot + 90) % 360;
        if (pdfDoc) pdfDoc.getPage(1).then(p => renderPage(p));
    }

    function toggleFullScreen() {
        const viewer = document.getElementById('docViewerContainer');
        if (!document.fullscreenElement) {
            viewer.requestFullscreen().catch(err => console.error(err));
        } else {
            document.exitFullscreen();
        }
    }
</script>

@elseif(in_array(strtolower($document->file_type), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
<script>
    let currentScale = 1.0;
    let currentRot   = 0;
    const img = document.getElementById('imgViewer');

    function applyImgTransform() {
        img.style.transform = `scale(${currentScale}) rotate(${currentRot}deg)`;
        document.getElementById('zoomLevel').innerText = Math.round(currentScale * 100) + '%';
    }

    function adjustZoom(step) {
        currentScale = Math.max(0.5, Math.min(3.0, parseFloat((currentScale + step * 0.1).toFixed(1))));
        applyImgTransform();
    }

    function rotateDoc() {
        currentRot = (currentRot + 90) % 360;
        applyImgTransform();
    }

    function toggleFullScreen() {
        const viewer = document.getElementById('docViewerContainer');
        if (!document.fullscreenElement) {
            viewer.requestFullscreen().catch(err => console.error(err));
        } else {
            document.exitFullscreen();
        }
    }

    applyImgTransform();
</script>

@else
<script>
    function adjustZoom()     {}
    function rotateDoc()      {}
    function toggleFullScreen() {
        const viewer = document.getElementById('docViewerContainer');
        if (!document.fullscreenElement) {
            viewer.requestFullscreen().catch(err => console.error(err));
        } else {
            document.exitFullscreen();
        }
    }
</script>
@endif

@endsection
