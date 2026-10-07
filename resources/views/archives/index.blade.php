@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto pb-10">

    <!-- Header, Breadcrumb & Search -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Arsip</h1>
            <div class="flex items-center gap-2 text-xs text-slate-400 mt-1 font-medium">
                <span>Arsip</span>
                <span>&gt;</span>
                <span class="text-slate-600">{{ $selectedYear }}</span>
            </div>
        </div>

        <!-- Inline Folder Search -->
        <form action="{{ route('archives.index') }}" method="GET" class="w-full md:w-80">
            <input type="hidden" name="tahun" value="{{ $selectedYear }}">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                <input type="text" 
                       name="q" 
                       value="{{ $search }}"
                       placeholder="Cari folder..." 
                       class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-full text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005596] focus:border-transparent transition shadow-xs">
            </div>
        </form>
    </div>

    <!-- YEAR SELECTOR ROW -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach($yearsList as $yr)
            <a href="{{ route('archives.index', ['tahun' => $yr]) }}" 
               class="bg-white rounded-2xl p-5 shadow-xs transition duration-200 flex items-center gap-4 {{ $selectedYear == $yr ? 'border-2 border-[#005596] ring-2 ring-[#005596]/10' : 'border border-slate-100 hover:border-slate-300' }}">
                <div class="w-12 h-12 rounded-xl bg-[#fac000]/20 text-[#fac000] flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-solid fa-folder"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">{{ $yr }}</h3>
                    <p class="text-xs text-slate-400 font-medium">{{ $yearCounts[$yr] ?? 0 }} arsip</p>
                </div>
            </a>
        @endforeach
    </div>

    <!-- FOLDER GRID SECTION -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Folder Tahun {{ $selectedYear }}</h2>
            @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
                <a href="{{ route('folders.index') }}" class="text-xs font-semibold text-[#005596] hover:underline flex items-center gap-1">
                    <i class="fa-solid fa-gear text-[10px]"></i> Kelola Folder
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse($folders as $folder)
                <a href="{{ route('archives.folder', ['folder' => $folder->id, 'tahun' => $selectedYear]) }}" 
                   class="bg-white rounded-2xl p-6 shadow-xs border border-slate-100/90 hover:border-sky-300 hover:shadow-md transition-all duration-200 flex items-center gap-4 group">
                    <div class="w-14 h-14 rounded-2xl bg-[#fac000]/20 text-[#fac000] flex items-center justify-center text-3xl flex-shrink-0 group-hover:scale-105 transition duration-200">
                        <i class="fa-solid fa-folder"></i>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-sm font-bold text-slate-800 group-hover:text-[#005596] transition truncate">
                            {{ $folder->nama_folder }}
                        </h4>
                        <span class="text-xs text-slate-400 font-medium">
                            {{ $folder->documents_count }} arsip
                        </span>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white rounded-3xl p-10 text-center border border-slate-100">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mb-3">
                        <i class="fa-regular fa-folder-open"></i>
                    </div>
                    <h4 class="text-base font-bold text-slate-700">Tidak ada folder ditemukan</h4>
                    <p class="text-xs text-slate-400 mt-1">Belum ada folder untuk tahun {{ $selectedYear }} atau pencarian tidak sesuai.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
