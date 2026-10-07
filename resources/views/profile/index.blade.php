@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-12 space-y-6">

    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengaturan Akun</h1>

    <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-xs border border-slate-100/90">
        
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Avatar & Role Badge Header -->
            <div class="flex items-center gap-4 pb-6 border-b border-slate-100">
                <div class="w-16 h-16 rounded-full bg-[#0284c7] text-white flex items-center justify-center text-2xl font-bold shadow-sm flex-shrink-0">
                    <i class="fa-regular fa-user"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">{{ $user->nama_lengkap }}</h2>
                    <p class="text-xs text-slate-500">{{ $user->email }} &bull; <span class="text-[#005596] font-bold">{{ $user->role_display_name }}</span></p>
                </div>
            </div>

            <!-- Profile Info Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Username <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">NIP / NIK</label>
                    <input type="text" name="nip_nik" value="{{ old('nip_nik', $user->nip_nik) }}" placeholder="Opsional" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="pt-6 border-t border-slate-100">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Ganti Password (Opsional)</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Password Lama</label>
                        <input type="password" name="current_password" placeholder="Masukkan password saat ini" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Password Baru</label>
                            <input type="password" name="new_password" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" placeholder="Ulangi password baru" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end pt-4">
                <button type="submit" class="px-8 py-3 rounded-xl bg-[#fac000] hover:bg-[#e5af00] text-slate-900 font-bold text-sm shadow-xs transition duration-150">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>

</div>
@endsection
