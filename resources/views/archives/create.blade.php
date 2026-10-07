@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto pb-12">

    <!-- Card Container -->
    <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-xs border border-slate-100/90">
        
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-8">
            Tambah Arsip
        </h1>

        <form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- ROW 1: Nama Dokumen & Nomor Dokumen -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama_dokumen" class="block text-xs font-bold text-slate-700 mb-2">
                        Nama Dokumen <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_dokumen" 
                           id="nama_dokumen" 
                           value="{{ old('nama_dokumen') }}"
                           required 
                           placeholder="Masukkan nama dokumen"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005596] focus:border-transparent transition">
                </div>

                <div>
                    <label for="nomor_dokumen" class="block text-xs font-bold text-slate-700 mb-2">
                        Nomor Dokumen
                    </label>
                    <input type="text" 
                           name="nomor_dokumen" 
                           id="nomor_dokumen" 
                           value="{{ old('nomor_dokumen') }}"
                           placeholder="Masukkan nomor dokumen"
                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005596] focus:border-transparent transition">
                </div>
            </div>

            <!-- ROW 2: Tanggal Dokumen & Tahun -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tanggal_dokumen" class="block text-xs font-bold text-slate-700 mb-2">
                        Tanggal Dokumen <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <input type="date" 
                               name="tanggal_dokumen" 
                               id="tanggal_dokumen" 
                               value="{{ old('tanggal_dokumen', date('Y-m-d')) }}"
                               required 
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005596] focus:border-transparent transition">
                    </div>
                </div>

                <div>
                    <label for="tahun" class="block text-xs font-bold text-slate-700 mb-2">
                        Tahun <span class="text-rose-500">*</span>
                    </label>
                    <select name="tahun" 
                            id="tahun" 
                            required
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#005596] focus:border-transparent transition">
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" {{ old('tahun', $preselectedYear) == $yr ? 'selected' : '' }}>
                                {{ $yr }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- ROW 3: Folder -->
            <div>
                <label for="folder_id" class="block text-xs font-bold text-slate-700 mb-2">
                    Folder <span class="text-rose-500">*</span>
                </label>
                <select name="folder_id" 
                        id="folder_id" 
                        required
                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#005596] focus:border-transparent transition">
                    <option value="" disabled {{ !$preselectedFolderId ? 'selected' : '' }}>Pilih Folder</option>
                    @foreach($folders as $f)
                        <option value="{{ $f->id }}" {{ old('folder_id', $preselectedFolderId) == $f->id ? 'selected' : '' }}>
                            {{ $f->nama_folder }} ({{ $f->tahun }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- ROW 4: Keterangan -->
            <div>
                <label for="keterangan" class="block text-xs font-bold text-slate-700 mb-2">
                    Keterangan
                </label>
                <input type="text" 
                       name="keterangan" 
                       id="keterangan" 
                       value="{{ old('keterangan') }}"
                       placeholder="Masukkan keterangan (opsional)"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005596] focus:border-transparent transition">
            </div>

            <!-- ROW 5: Drag & Drop File Scan Dropzone -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">
                    File Scan <span class="text-rose-500">*</span>
                </label>
                
                <div id="dropZone" 
                     onclick="document.getElementById('file_scan').click()"
                     class="border-2 border-dashed border-sky-300 hover:border-sky-500 rounded-2xl bg-sky-50/30 hover:bg-sky-50/60 p-8 sm:p-12 text-center cursor-pointer transition-all duration-200 flex flex-col items-center justify-center">
                    
                    <input type="file" 
                           name="file_scan" 
                           id="file_scan" 
                           required 
                           accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx" 
                           class="hidden" 
                           onchange="handleFileSelect(this)">

                    <div class="w-14 h-14 rounded-2xl bg-sky-100 text-[#0284c7] flex items-center justify-center text-2xl mb-3 shadow-xs">
                        <i id="uploadIcon" class="fa-regular fa-file-lines"></i>
                    </div>

                    <h4 id="filePromptTitle" class="text-sm font-bold text-slate-800 mb-1">
                        Klik untuk memilih file atau drag & drop
                    </h4>
                    <p id="filePromptSub" class="text-xs text-slate-400">
                        Format: PDF, JPG, PNG (Maks. 10MB)
                    </p>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ url()->previous() }}" 
                   class="px-7 py-3 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-sm transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 rounded-xl bg-[#fac000] hover:bg-[#e5af00] text-slate-900 font-bold text-sm shadow-xs transition duration-150">
                    Simpan
                </button>
            </div>
        </form>

    </div>

</div>

<script>
    function handleFileSelect(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById('filePromptTitle').innerText = file.name;
            document.getElementById('filePromptSub').innerText = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            document.getElementById('dropZone').classList.add('border-emerald-400', 'bg-emerald-50/40');
            document.getElementById('uploadIcon').className = 'fa-solid fa-circle-check text-emerald-500 text-2xl';
        }
    }

    // Drag and drop handlers
    const dropZone = document.getElementById('dropZone');
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('border-sky-600', 'bg-sky-100/50');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-sky-600', 'bg-sky-100/50');
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length) {
            document.getElementById('file_scan').files = files;
            handleFileSelect(document.getElementById('file_scan'));
        }
    }, false);
</script>
@endsection
