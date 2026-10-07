@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kelola User</h1>
        
        <button type="button" 
                onclick="openCreateUserModal()"
                class="inline-flex items-center gap-2 bg-[#fac000] hover:bg-[#e5af00] text-slate-900 font-bold px-5 py-2.5 rounded-xl shadow-xs transition duration-150 text-sm">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah User</span>
        </button>
    </div>

    <!-- USERS TABLE CARD -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-100/90 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-500">
                        <th class="pb-4 w-16 text-center">No</th>
                        <th class="pb-4">Nama</th>
                        <th class="pb-4">Username</th>
                        <th class="pb-4">Role</th>
                        <th class="pb-4 text-center">Status</th>
                        <th class="pb-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm font-medium">
                    @forelse($users as $index => $u)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 text-center text-slate-500 font-semibold">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-4 font-bold text-slate-800">
                                {{ $u->nama_lengkap }}
                            </td>
                            <td class="py-4 text-slate-500 font-normal">
                                {{ $u->username }}
                            </td>
                            <td class="py-4 text-slate-700">
                                {{ $u->role_display_name }}
                            </td>
                            <td class="py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $u->status === 'Aktif' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $u->status }}
                                </span>
                            </td>
                            <td class="py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit Button -->
                                    <button type="button" 
                                            onclick="openEditUserModal({{ json_encode($u) }})"
                                            title="Edit Pengguna"
                                            class="w-8 h-8 rounded-lg bg-sky-50 hover:bg-sky-100 text-[#0284c7] flex items-center justify-center transition border border-sky-100">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>

                                    <!-- Delete Button (Only for other users) -->
                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Hapus Pengguna"
                                                    class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-500 flex items-center justify-center transition border border-rose-100">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400 text-sm">
                                Belum ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- CREATE USER MODAL -->
<div id="createUserModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-extrabold text-slate-900">Tambah User</h3>
            <button type="button" onclick="closeCreateUserModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_lengkap" required placeholder="Contoh: Budi Santoso" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Username <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" required placeholder="budi" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" required placeholder="budi@pln.co.id" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Role <span class="text-rose-500">*</span></label>
                    <select name="role_id" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}">{{ $r->nama_role }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                        <option value="Aktif" selected>Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NIP / NIK</label>
                    <input type="text" name="nip_nik" placeholder="Opsional" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeCreateUserModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#fac000] hover:bg-[#e5af00] text-slate-900 font-bold text-xs transition">Simpan User</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT USER MODAL -->
<div id="editUserModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-extrabold text-slate-900">Edit User</h3>
            <button type="button" onclick="closeEditUserModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="editUserForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_nama_lengkap" name="nama_lengkap" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Username <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_username" name="username" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email <span class="text-rose-500">*</span></label>
                    <input type="email" id="edit_email" name="email" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Role <span class="text-rose-500">*</span></label>
                    <select id="edit_role_id" name="role_id" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}">{{ $r->nama_role }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status <span class="text-rose-500">*</span></label>
                    <select id="edit_status" name="status" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Password Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NIP / NIK</label>
                    <input type="text" id="edit_nip_nik" name="nip_nik" placeholder="Opsional" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditUserModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#fac000] hover:bg-[#e5af00] text-slate-900 font-bold text-xs transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateUserModal() {
        document.getElementById('createUserModal').classList.remove('hidden');
    }
    function closeCreateUserModal() {
        document.getElementById('createUserModal').classList.add('hidden');
    }

    function openEditUserModal(user) {
        document.getElementById('edit_nama_lengkap').value = user.nama_lengkap;
        document.getElementById('edit_username').value = user.username;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role_id').value = user.role_id;
        document.getElementById('edit_status').value = user.status;
        document.getElementById('edit_nip_nik').value = user.nip_nik || '';
        document.getElementById('editUserForm').action = `/users/${user.id}`;
        document.getElementById('editUserModal').classList.remove('hidden');
    }
    function closeEditUserModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }
</script>
@endsection
