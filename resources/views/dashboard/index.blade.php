@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto pb-10">

    <!-- Header & Greeting -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            @if(auth()->user()->isPetugas() || auth()->user()->isPegawai())
                <div class="flex items-center gap-2 text-xs text-slate-400 mb-1 font-medium">
                    <a href="{{ route('archives.index') }}" class="hover:text-slate-600">Arsip</a>
                    <span>&gt;</span>
                    <span class="text-slate-600">{{ $activeYear }}</span>
                </div>
            @endif

            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard</h1>
            
            @if(auth()->user()->isAdmin())
                <p class="text-sm text-slate-500 mt-1 font-medium">
                    Selamat datang, <strong class="text-slate-700">{{ auth()->user()->nama_lengkap }}</strong> &#128075;
                </p>
                <p class="text-xs text-slate-400 mt-0.5">Kelola arsip dengan lebih mudah dan efisien.</p>
            @elseif(auth()->user()->isPetugas())
                <p class="text-sm text-slate-500 mt-1 font-medium">
                    Halo, <strong class="text-slate-700">{{ auth()->user()->nama_lengkap }}</strong>
                </p>
                <p class="text-xs text-slate-400 mt-0.5">{{ auth()->user()->role_display_name }}</p>
            @else
                <p class="text-sm text-slate-500 mt-1 font-medium">
                    Selamat datang, <strong class="text-slate-700">{{ auth()->user()->nama_lengkap }}</strong>
                </p>
                <p class="text-xs text-slate-400 mt-0.5">{{ auth()->user()->role_display_name }}</p>
            @endif
        </div>

        @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
            <div>
                <a href="{{ route('archives.create') }}" 
                   class="inline-flex items-center gap-2 bg-[#fac000] hover:bg-[#e5af00] text-slate-900 font-bold px-5 py-2.5 rounded-xl shadow-xs transition duration-150 text-sm">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Upload Arsip</span>
                </a>
            </div>
        @endif
    </div>

    <!-- STAT CARDS SECTION -->
    @if(auth()->user()->isAdmin())
        <!-- Admin: 4 Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Arsip -->
            <div class="bg-white rounded-2xl p-6 shadow-xs border border-slate-100/80 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-[#0284c7] flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-regular fa-file-lines"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 block mb-0.5">Total Arsip</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-black text-slate-800">{{ number_format($totalArsip, 0, ',', '.') }}</span>
                        <span class="text-xs text-slate-400 font-medium">Dokumen</span>
                    </div>
                </div>
            </div>

            <!-- Tahun Aktif -->
            <div class="bg-white rounded-2xl p-6 shadow-xs border border-slate-100/80 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-[#0284c7] flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-regular fa-calendar"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 block mb-0.5">Tahun Aktif</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-black text-slate-800">{{ $activeYear }}</span>
                        <span class="text-xs text-slate-400 font-medium">Tahun</span>
                    </div>
                </div>
            </div>

            <!-- Folder -->
            <div class="bg-white rounded-2xl p-6 shadow-xs border border-slate-100/80 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-[#0284c7] flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-regular fa-folder"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 block mb-0.5">Folder</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-black text-slate-800">{{ $totalFolder }}</span>
                        <span class="text-xs text-slate-400 font-medium">Kategori</span>
                    </div>
                </div>
            </div>

            <!-- Pengguna -->
            <div class="bg-white rounded-2xl p-6 shadow-xs border border-slate-100/80 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-[#0284c7] flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-solid fa-user-group text-xl"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 block mb-0.5">Pengguna</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-black text-slate-800">{{ $totalUser }}</span>
                        <span class="text-xs text-slate-400 font-medium">User</span>
                    </div>
                </div>
            </div>
        </div>

    @elseif(auth()->user()->isPetugas())
        <!-- Petugas Arsip: 3 Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl p-6 shadow-xs border border-slate-100/80 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-500 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-xs">
                    <i class="fa-regular fa-file-lines"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 block mb-0.5">Total Arsip</span>
                    <span class="text-2xl font-black text-slate-800">{{ number_format($totalArsip, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-xs border border-slate-100/80 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-500 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-xs">
                    <i class="fa-solid fa-arrow-up-from-bracket text-xl"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 block mb-0.5">Upload Hari Ini</span>
                    <span class="text-2xl font-black text-slate-800">{{ $uploadHariIni }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-xs border border-slate-100/80 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-500 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-xs">
                    <i class="fa-solid fa-folder text-xl"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 block mb-0.5">Folder</span>
                    <span class="text-2xl font-black text-slate-800">{{ $totalFolder }}</span>
                </div>
            </div>
        </div>

    @else
        <!-- Pegawai: 3 Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl p-6 shadow-xs border border-slate-100/80 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-500 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-xs">
                    <i class="fa-regular fa-file-lines"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 block mb-0.5">Total Arsip</span>
                    <span class="text-2xl font-black text-slate-800">{{ number_format($totalArsip, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-xs border border-slate-100/80 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-500 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-xs">
                    <i class="fa-solid fa-folder text-xl"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 block mb-0.5">Folder</span>
                    <span class="text-2xl font-black text-slate-800">{{ $totalFolder }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-xs border border-slate-100/80 flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-500 text-white flex items-center justify-center text-2xl flex-shrink-0 shadow-xs">
                    <i class="fa-regular fa-calendar text-xl"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 block mb-0.5">Tahun Aktif</span>
                    <span class="text-2xl font-black text-slate-800">{{ $activeYear }}</span>
                </div>
            </div>
        </div>

        <!-- Folder Populer (Pegawai View) -->
        <div class="space-y-3">
            <h3 class="text-base font-bold text-slate-900">Folder Populer</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($popularFolders as $pFolder)
                    <a href="{{ route('archives.folder', ['folder' => $pFolder->id, 'tahun' => $pFolder->tahun]) }}" 
                       class="bg-white rounded-2xl p-5 shadow-xs border border-slate-100/80 hover:border-sky-300 hover:shadow-md transition flex items-center gap-4 group">
                        <div class="w-12 h-12 rounded-xl bg-[#fac000]/20 text-[#fac000] flex items-center justify-center text-2xl flex-shrink-0 group-hover:scale-110 transition">
                            <i class="fa-solid fa-folder"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-[#005596] transition">{{ $pFolder->nama_folder }}</h4>
                            <span class="text-xs text-slate-400">{{ $pFolder->documents_count }} arsip</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- MAIN TABLE / ACTIVITY SECTION -->
    @if(auth()->user()->isPetugas())
        <!-- Petugas Arsip: Aktivitas Terbaru List -->
        <div class="bg-white rounded-3xl p-6 shadow-xs border border-slate-100/80">
            <h3 class="text-base font-bold text-slate-900 mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-3">
                @forelse($arsipTerbaru as $doc)
                    <div class="flex items-center justify-between p-4 bg-white hover:bg-slate-50 rounded-2xl border border-slate-100 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-[#ee3e38] text-white flex items-center justify-center font-black text-xs shadow-xs flex-shrink-0">
                                {{ strtoupper($doc->file_type) }}
                            </div>
                            <div>
                                <a href="{{ route('archives.show', $doc->id) }}" class="text-sm font-bold text-slate-800 hover:text-[#005596] transition block">
                                    {{ $doc->judul_dokumen }}
                                </a>
                                <span class="text-xs text-slate-400">Folder: {{ $doc->folder?->nama_folder ?? '-' }} &bull; Oleh: {{ $doc->uploader?->nama_lengkap ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="text-right text-xs text-slate-400 font-medium">
                            <span>{{ $doc->created_at->format('d/m/Y') }}</span>
                            <span class="ml-2">{{ $doc->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-sm">Belum ada aktivitas dokumen terbaru.</div>
                @endforelse
            </div>
        </div>

    @else
        <!-- Admin & Pegawai: Arsip Terbaru Table -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-100/80">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-900">Arsip Terbaru</h3>
                <a href="{{ route('archives.index') }}" class="text-xs font-bold text-[#005596] hover:underline">
                    Lihat Semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="pb-3 font-semibold pl-2">Nama Dokumen</th>
                            <th class="pb-3 font-semibold">Folder</th>
                            <th class="pb-3 font-semibold">Tanggal Upload</th>
                            <th class="pb-3 font-semibold text-right pr-2">Uploader</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($arsipTerbaru as $doc)
                            <tr class="hover:bg-slate-50/70 transition group">
                                <td class="py-4 pl-2">
                                    <a href="{{ route('archives.show', $doc->id) }}" class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-amber-400/20 text-[#fac000] flex items-center justify-center text-sm flex-shrink-0">
                                            <i class="fa-solid fa-folder"></i>
                                        </div>
                                        <span class="font-bold text-slate-800 group-hover:text-[#005596] transition">{{ $doc->judul_dokumen }}</span>
                                    </a>
                                </td>
                                <td class="py-4 text-slate-600 font-medium">
                                    {{ $doc->folder?->nama_folder ?? '-' }}
                                </td>
                                <td class="py-4 text-slate-500 text-xs">
                                    {{ $doc->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-4 text-right pr-2 text-slate-700 font-medium text-xs">
                                    {{ $doc->uploader?->nama_lengkap ?? 'Admin' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 text-sm">
                                    Belum ada arsip dokumen yang diunggah.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
