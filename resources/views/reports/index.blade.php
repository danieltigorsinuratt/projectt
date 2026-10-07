@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Laporan & Log Aktivitas</h1>
            <p class="text-xs text-slate-500 mt-1">Pantau seluruh riwayat interaksi dan aktivitas sistem pengarsipan dokumen.</p>
        </div>

        <button type="button" 
                onclick="window.print()"
                class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 font-bold px-5 py-2.5 rounded-xl border border-slate-200 shadow-xs transition duration-150 text-sm">
            <i class="fa-solid fa-print text-xs"></i>
            <span>Cetak Laporan</span>
        </button>
    </div>

    <!-- FILTER CARD -->
    <div class="bg-white rounded-3xl p-6 shadow-xs border border-slate-100/90">
        <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#005596]">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#005596]">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Pengguna</label>
                <select name="user_id" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#005596]">
                    <option value="">Semua Pengguna</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ $userId == $u->id ? 'selected' : '' }}>
                            {{ $u->nama_lengkap }} ({{ $u->username }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-[#005596] hover:bg-[#004780] text-white font-bold text-xs rounded-xl transition">
                    Filter
                </button>
                <a href="{{ route('reports.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- LOGS TABLE CARD -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-100/90 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-500">
                        <th class="pb-4 w-12 text-center">No</th>
                        <th class="pb-4">Waktu</th>
                        <th class="pb-4">Pengguna</th>
                        <th class="pb-4">Aksi</th>
                        <th class="pb-4">Keterangan</th>
                        <th class="pb-4 text-right">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm font-medium">
                    @forelse($logs as $index => $log)
                        <tr class="hover:bg-slate-50/70 transition text-xs">
                            <td class="py-4 text-center text-slate-400 font-semibold">
                                {{ ($logs->currentPage() - 1) * $logs->perPage() + $index + 1 }}
                            </td>
                            <td class="py-4 text-slate-600 whitespace-nowrap">
                                <span class="font-bold text-slate-800">{{ $log->created_at->format('d/m/Y') }}</span>
                                <span class="text-slate-400 ml-1">{{ $log->created_at->format('H:i') }}</span>
                            </td>
                            <td class="py-4 text-slate-800">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-sky-100 text-sky-700 font-bold text-[10px] flex items-center justify-center flex-shrink-0">
                                        {{ substr($log->user?->nama_lengkap ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="font-bold">{{ $log->user?->nama_lengkap ?? 'User' }}</span>
                                </div>
                            </td>
                            <td class="py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold 
                                    {{ str_contains(strtolower($log->aksi), 'upload') ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                    {{ str_contains(strtolower($log->aksi), 'hapus') ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}
                                    {{ str_contains(strtolower($log->aksi), 'login') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                    {{ !str_contains(strtolower($log->aksi), 'upload') && !str_contains(strtolower($log->aksi), 'hapus') && !str_contains(strtolower($log->aksi), 'login') ? 'bg-sky-50 text-sky-700 border border-sky-200' : '' }}">
                                    {{ $log->aksi }}
                                </span>
                            </td>
                            <td class="py-4 text-slate-600 max-w-xs truncate">
                                {{ $log->keterangan ?: '-' }}
                            </td>
                            <td class="py-4 text-right text-slate-400 font-mono text-[11px]">
                                {{ $log->ip_address ?: '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400 text-sm">
                                Tidak ada log aktivitas yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 pt-4 border-t border-slate-100">
            {{ $logs->links('pagination::tailwind') }}
        </div>
    </div>

</div>
@endsection
