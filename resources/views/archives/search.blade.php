@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    <!-- Search Input Bar (Matching Pencarian folder.png) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-100/90">
        <form action="{{ route('archives.search') }}" method="GET" class="relative">
            <div class="relative flex items-center">
                <i class="fa-solid fa-magnifying-glass absolute left-5 text-slate-400 text-lg"></i>
                <input type="text" 
                       name="q" 
                       value="{{ $query }}"
                       placeholder="Cari dokumen, nama file, nomor, atau folder..." 
                       class="w-full pl-14 pr-12 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-base text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005596] focus:bg-white transition"
                       autofocus>
                @if($query)
                    <a href="{{ route('archives.search') }}" class="absolute right-4 text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-circle-xmark text-lg"></i>
                    </a>
                @endif
            </div>
        </form>

        <!-- Pencarian Populer Tags -->
        <div class="mt-6">
            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Pencarian Populer</h4>
            <div class="flex flex-wrap gap-2.5">
                <a href="{{ route('archives.search', ['q' => 'TUG 9']) }}" class="px-5 py-2 rounded-full bg-sky-50 hover:bg-sky-100 text-[#005596] text-xs font-semibold transition border border-sky-100">
                    TUG 9
                </a>
                <a href="{{ route('archives.search', ['q' => 'TUG 4']) }}" class="px-5 py-2 rounded-full bg-sky-50 hover:bg-sky-100 text-[#005596] text-xs font-semibold transition border border-sky-100">
                    TUG 4
                </a>
                <a href="{{ route('archives.search', ['q' => 'Berita Acara']) }}" class="px-5 py-2 rounded-full bg-sky-50 hover:bg-sky-100 text-[#005596] text-xs font-semibold transition border border-sky-100">
                    Berita Acara
                </a>
                <a href="{{ route('archives.search', ['q' => '2026']) }}" class="px-5 py-2 rounded-full bg-sky-50 hover:bg-sky-100 text-[#005596] text-xs font-semibold transition border border-sky-100">
                    2026
                </a>
            </div>
        </div>
    </div>

    <!-- Hasil Pencarian List (Matching Pencarian folder.png) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-100/90">
        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Hasil Pencarian</h3>

        <div class="space-y-3">
            @forelse($results as $doc)
                <div class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100/80 rounded-2xl transition border border-slate-100 group">
                    <a href="{{ route('archives.show', $doc->id) }}" class="flex items-center gap-4 min-w-0 flex-1">
                        <div class="w-12 h-12 rounded-xl bg-[#ee3e38] text-white flex items-center justify-center font-black text-xs flex-shrink-0 shadow-xs">
                            {{ strtoupper($doc->file_type) }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-sm font-bold text-slate-800 group-hover:text-[#005596] transition truncate">
                                {{ $doc->judul_dokumen }}
                            </h4>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $doc->folder?->nama_folder ?? '-' }} &bull; {{ $doc->tanggal_dokumen ? $doc->tanggal_dokumen->format('d/m/Y') : $doc->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </a>

                    <div class="flex items-center gap-2 ml-4">
                        <a href="{{ route('archives.download', $doc->id) }}" 
                           title="Download"
                           class="w-10 h-10 rounded-xl bg-white hover:bg-sky-50 text-slate-400 hover:text-[#005596] flex items-center justify-center transition border border-slate-200/80">
                            <i class="fa-solid fa-download text-sm"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-slate-400 text-sm">
                    @if($query)
                        Tidak ditemukan arsip dokumen yang cocok dengan kata kunci "<strong>{{ $query }}</strong>".
                    @else
                        Silakan ketik nama dokumen, nomor, atau pilih dari pencarian populer di atas.
                    @endif
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
