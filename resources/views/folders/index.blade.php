@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kelola Folder</h1>
        
        <button type="button" 
                onclick="openCreateModal()"
                class="inline-flex items-center gap-2 bg-[#fac000] hover:bg-[#e5af00] text-slate-900 font-bold px-5 py-2.5 rounded-xl shadow-xs transition duration-150 text-sm">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Folder</span>
        </button>
    </div>

    <!-- FOLDER TABLE CARD -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-100/90 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-500">
                        <th class="pb-4 w-16 text-center">No</th>
                        <th class="pb-4">Nama Folder</th>
                        <th class="pb-4">Tahun</th>
                        <th class="pb-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm font-medium">
                    @forelse($folders as $index => $folder)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 text-center text-slate-500 font-semibold">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-4 font-bold text-slate-800">
                                <a href="{{ route('archives.folder', ['folder' => $folder->id, 'tahun' => $folder->tahun]) }}" class="hover:text-[#005596] transition">
                                    {{ $folder->nama_folder }}
                                </a>
                            </td>
                            <td class="py-4 text-slate-600">
                                {{ $folder->tahun }}
                            </td>
                            <td class="py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit Button -->
                                    <button type="button" 
                                            onclick="openEditModal({{ json_encode($folder) }})"
                                            title="Edit Folder"
                                            class="w-8 h-8 rounded-lg bg-sky-50 hover:bg-sky-100 text-[#0284c7] flex items-center justify-center transition border border-sky-100">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('folders.destroy', $folder->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus folder ini beserta isinya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Folder"
                                                class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-500 flex items-center justify-center transition border border-rose-100">
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-slate-400 text-sm">
                                Belum ada folder terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Pagination simulation -->
        <div class="flex items-center justify-end gap-2 mt-8 pt-4 border-t border-slate-100 text-xs">
            <span class="w-8 h-8 rounded-lg bg-[#005596] text-white font-bold flex items-center justify-center">1</span>
        </div>
    </div>

</div>

<!-- CREATE FOLDER MODAL -->
<div id="createFolderModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100 p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-extrabold text-slate-900">Tambah Folder</h3>
            <button type="button" onclick="closeCreateModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('folders.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Folder <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_folder" required placeholder="Contoh: TUG 9, Berita Acara" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun <span class="text-rose-500">*</span></label>
                <input type="text" name="tahun" required value="2026" placeholder="Contoh: 2026 atau 2024 - 2026" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Keterangan isi folder..." class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#fac000] hover:bg-[#e5af00] text-slate-900 font-bold text-xs transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT FOLDER MODAL -->
<div id="editFolderModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100 p-6 sm:p-8 animate-in fade-in zoom-in-95 duration-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-extrabold text-slate-900">Edit Folder</h3>
            <button type="button" onclick="closeEditModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="editFolderForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Folder <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_nama_folder" name="nama_folder" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tahun <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_tahun" name="tahun" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea id="edit_deskripsi" name="deskripsi" rows="3" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#005596]"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#fac000] hover:bg-[#e5af00] text-slate-900 font-bold text-xs transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createFolderModal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('createFolderModal').classList.add('hidden');
    }

    function openEditModal(folder) {
        document.getElementById('edit_nama_folder').value = folder.nama_folder;
        document.getElementById('edit_tahun').value = folder.tahun;
        document.getElementById('edit_deskripsi').value = folder.deskripsi || '';
        document.getElementById('editFolderForm').action = `/folders/${folder.id}`;
        document.getElementById('editFolderModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editFolderModal').classList.add('hidden');
    }
</script>
@endsection
