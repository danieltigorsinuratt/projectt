@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-10">

    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
        <a href="{{ route('archives.index') }}" class="hover:text-slate-600">Arsip</a>
        <span>&gt;</span>
        <a href="{{ route('archives.index', ['tahun' => $selectedYear]) }}" class="hover:text-slate-600">{{ $selectedYear }}</a>
        <span>&gt;</span>
        <span class="text-slate-600 font-bold">{{ $folder->nama_folder }}</span>
    </div>

    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
            {{ $folder->nama_folder }} - {{ $selectedYear }}
        </h1>
        
        @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
            <a href="{{ route('archives.create', ['folder_id' => $folder->id, 'tahun' => $selectedYear]) }}" 
               class="inline-flex items-center gap-2 bg-[#fac000] hover:bg-[#e5af00] text-slate-900 font-bold px-5 py-2.5 rounded-xl shadow-xs transition duration-150 text-sm flex-shrink-0">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Upload Arsip</span>
            </a>
        @endif
    </div>

    <!-- Search Input Filter -->
    <form action="{{ route('archives.folder', $folder->id) }}" method="GET" class="w-full max-w-md">
        <input type="hidden" name="tahun" value="{{ $selectedYear }}">
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-slate-400 text-sm"></i>
            <input type="text" 
                   name="q" 
                   value="{{ $search }}"
                   placeholder="Cari nama dokumen, nomor, atau tanggal..." 
                   class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-full text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005596] focus:border-transparent transition shadow-xs">
        </div>
    </form>

    <!-- DOCUMENTS TABLE CARD -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-100/90 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-500">
                        <th class="pb-4 w-12 text-center">No</th>
                        <th class="pb-4">Nama Dokumen</th>
                        <th class="pb-4">Nomor Dokumen</th>
                        <th class="pb-4">Tanggal</th>
                        <th class="pb-4 text-center">File</th>
                        <th class="pb-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm font-medium">
                    @forelse($documents as $index => $doc)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 text-center text-slate-500 font-semibold">
                                {{ ($documents->currentPage() - 1) * $documents->perPage() + $index + 1 }}
                            </td>
                            <td class="py-4 font-bold text-slate-800">
                                <a href="{{ route('archives.show', $doc->id) }}" class="hover:text-[#005596] transition">
                                    {{ $doc->judul_dokumen }}
                                </a>
                            </td>
                            <td class="py-4 text-slate-600">
                                {{ $doc->nomor_dokumen ?: '-' }}
                            </td>
                            <td class="py-4 text-slate-500 text-xs">
                                {{ $doc->tanggal_dokumen ? $doc->tanggal_dokumen->format('d/m/Y') : $doc->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-4 text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-[#ee3e38] text-white text-[10px] font-black tracking-wide shadow-2xs">
                                    <i class="fa-regular fa-file-pdf mr-1 text-[11px]"></i> {{ strtoupper($doc->file_type) }}
                                </span>
                            </td>
                            <td class="py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <!-- Download Button -->
                                    <a href="{{ route('archives.download', $doc->id) }}" 
                                       title="Download Dokumen"
                                       class="text-slate-400 hover:text-[#005596] transition">
                                        <i class="fa-solid fa-download text-sm"></i>
                                    </a>

                                    <!-- Preview Button -->
                                    <a href="{{ route('archives.show', $doc->id) }}" 
                                       title="Buka / Pratinjau Dokumen"
                                       class="text-slate-400 hover:text-[#005596] transition">
                                        <i class="fa-regular fa-eye text-sm"></i>
                                    </a>

                                    @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
                                        <!-- Delete Button -->
                                        <form action="{{ route('archives.destroy', $doc->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip dokumen ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Dokumen" class="text-slate-400 hover:text-rose-600 transition">
                                                <i class="fa-regular fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 text-sm">
                                <i class="fa-regular fa-folder-open text-3xl mb-2 text-slate-300 block"></i>
                                Belum ada dokumen dalam folder ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION & SUMMARY -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-8 pt-4 border-t border-slate-100 text-xs text-slate-500">
            <div>
                Menampilkan 
                <span class="font-bold text-slate-700">{{ $documents->firstItem() ?? 0 }}</span> 
                - 
                <span class="font-bold text-slate-700">{{ $documents->lastItem() ?? 0 }}</span> 
                dari 
                <span class="font-bold text-slate-700">{{ $documents->total() }}</span> data
            </div>

            <div>
                {{ $documents->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

</div>
@endsection
