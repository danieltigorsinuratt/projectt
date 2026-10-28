<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f4f6fa]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistem Pengarsipan Dokumen' }} - PLN Divisi Jaringan Konstruksi</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN for instant styling + Vite support -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        pln: {
                            blue: '#005ba4',
                            navy: '#004780',
                            dark: '#003a6a',
                            yellow: '#fac000',
                            yellowHover: '#e5af00',
                            lightBg: '#f4f6fa',
                            cardBg: '#ffffff',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="h-full text-slate-800 antialiased flex overflow-hidden">

    <!-- SIDEBAR (PLN Rich Deep Blue) -->
    <aside class="w-64 bg-[#005596] flex-shrink-0 flex flex-col justify-between shadow-xl z-20 transition-all duration-300">
        <div class="flex flex-col h-full">
            <!-- Brand Logo -->
            <div class="px-6 py-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#fac000] flex items-center justify-center shadow-md relative overflow-hidden flex-shrink-0">
                    <!-- PLN Lightning & Waves Vector -->
                    <svg class="w-7 h-7 text-red-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M13 2L3 14h7v8l11-13h-8l0-7z" fill="#dc2626"/>
                        <path d="M4 17h16v1H4zm0 3h16v1H4z" fill="#0284c7"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl font-black tracking-wider text-white">PLN</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-2 space-y-1.5 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#fac000] text-slate-900 shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-house text-base w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Arsip -->
                <a href="{{ route('archives.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('archives.index') || request()->routeIs('archives.folder') || request()->routeIs('archives.show') ? 'bg-[#fac000] text-slate-900 shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-box-archive text-base w-5 text-center"></i>
                    <span>Arsip</span>
                </a>

                <!-- Upload Arsip -->
                <a href="{{ route('archives.create') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('archives.create') ? 'bg-[#fac000] text-slate-900 shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-arrow-up-from-bracket text-base w-5 text-center"></i>
                    <span>Upload Arsip</span>
                </a>

                @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
                <!-- Kelola Folder -->
                <a href="{{ route('folders.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('folders.index') ? 'bg-[#fac000] text-slate-900 shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-folder-plus text-base w-5 text-center"></i>
                    <span>Kelola Folder</span>
                </a>
                @endif

                @if(auth()->user()->isAdmin())
                <!-- Kelola User -->
                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('users.index') ? 'bg-[#fac000] text-slate-900 shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-user-group text-base w-5 text-center"></i>
                    <span>Kelola User</span>
                </a>
                @endif

                @if(auth()->user()->isAdmin() || auth()->user()->isPetugas())
                <!-- Laporan -->
                <a href="{{ route('reports.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('reports.index') ? 'bg-[#fac000] text-slate-900 shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-chart-column text-base w-5 text-center"></i>
                    <span>Laporan</span>
                </a>
                @endif

                <!-- Pengaturan -->
                <a href="{{ route('profile.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('profile.index') ? 'bg-[#fac000] text-slate-900 shadow-sm' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <i class="fa-solid fa-gear text-base w-5 text-center"></i>
                    <span>Pengaturan</span>
                </a>
            </nav>

            <!-- Bottom Logout -->
            <div class="p-4 border-t border-white/10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 transition duration-200">
                        <i class="fa-solid fa-arrow-right-from-bracket text-base w-5 text-center"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- TOP NAVBAR -->
        <header class="h-20 bg-white/70 backdrop-blur-md border-b border-slate-200/60 flex items-center justify-between px-8 flex-shrink-0 z-10">
            <!-- Global Search Bar Input -->
            <div class="w-full max-w-md relative">
                <div class="relative flex items-center">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 text-slate-400 text-sm"></i>
                    <input type="text" 
                           id="globalSearchInput"
                           placeholder="Cari dokumen, nama file, atau nomor..." 
                           class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-full text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005596] focus:border-transparent transition shadow-xs"
                           onclick="openSearchModal()"
                           readonly>
                </div>
            </div>

            <!-- Right Header Actions -->
            <div class="flex items-center gap-6">
                <!-- User Profile Pill -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#0284c7] text-white flex items-center justify-center font-bold text-sm shadow-sm flex-shrink-0">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="text-sm font-bold text-slate-800 leading-tight">{{ auth()->user()->nama_lengkap }}</span>
                        <span class="text-xs text-slate-500">{{ auth()->user()->role_display_name }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN SCROLLABLE VIEW -->
        <main class="flex-1 overflow-y-auto px-8 py-6">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg"></i>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl shadow-xs">
                    <div class="flex items-center gap-3 mb-1">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                        <span class="text-sm font-bold">Terjadi kesalahan validasi:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 ml-6 text-rose-700">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Content Slot -->
            @yield('content')
        </main>
    </div>

    <!-- QUICK SEARCH MODAL (Matching 'Pencarian folder.png') -->
    <div id="searchModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden flex items-start justify-center pt-20 p-4 transition-all">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
            <!-- Modal Search Input -->
            <div class="p-5 border-b border-slate-100 flex items-center gap-3 relative">
                <i class="fa-solid fa-magnifying-glass text-slate-400 text-lg ml-2"></i>
                <input type="text" 
                       id="modalSearchQuery" 
                       placeholder="Cari dokumen, nama file, nomor, atau folder..." 
                       class="w-full text-base font-medium text-slate-800 placeholder-slate-400 focus:outline-none bg-transparent"
                       autofocus>
                <button type="button" onclick="closeSearchModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <div class="p-6">
                <!-- Popular Searches -->
                <div class="mb-6">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Pencarian Populer</h4>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="applyTag('TUG 9')" class="px-4 py-1.5 rounded-full bg-sky-50 hover:bg-sky-100 text-[#005596] text-xs font-semibold transition border border-sky-100">
                            TUG 9
                        </button>
                        <button type="button" onclick="applyTag('TUG 4')" class="px-4 py-1.5 rounded-full bg-sky-50 hover:bg-sky-100 text-[#005596] text-xs font-semibold transition border border-sky-100">
                            TUG 4
                        </button>
                        <button type="button" onclick="applyTag('Berita Acara')" class="px-4 py-1.5 rounded-full bg-sky-50 hover:bg-sky-100 text-[#005596] text-xs font-semibold transition border border-sky-100">
                            Berita Acara
                        </button>
                        <button type="button" onclick="applyTag('2026')" class="px-4 py-1.5 rounded-full bg-sky-50 hover:bg-sky-100 text-[#005596] text-xs font-semibold transition border border-sky-100">
                            2026
                        </button>
                    </div>
                </div>

                <!-- Search Results Section -->
                <div>
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Hasil Pencarian</h4>
                    <div id="searchResultsList" class="space-y-2.5 max-h-80 overflow-y-auto pr-1">
                        <div class="text-center py-8 text-slate-400 text-sm">
                            Ketik kata kunci untuk mencari dokumen...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts for Interactive Modals and Global Search -->
    <script>
        function openSearchModal() {
            document.getElementById('searchModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('modalSearchQuery').focus();
            }, 50);
        }

        function closeSearchModal() {
            document.getElementById('searchModal').classList.add('hidden');
        }

        // Close on Escape or outside click
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSearchModal();
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                openSearchModal();
            }
        });

        document.getElementById('searchModal').addEventListener('click', function(e) {
            if (e.target === this) closeSearchModal();
        });

        function applyTag(tag) {
            const input = document.getElementById('modalSearchQuery');
            input.value = tag;
            performLiveSearch(tag);
        }

        let searchTimeout = null;
        document.getElementById('modalSearchQuery').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const val = e.target.value;
            searchTimeout = setTimeout(() => {
                performLiveSearch(val);
            }, 250);
        });

        function performLiveSearch(query) {
            const container = document.getElementById('searchResultsList');
            if (!query.trim()) {
                container.innerHTML = '<div class="text-center py-8 text-slate-400 text-sm">Ketik kata kunci untuk mencari dokumen...</div>';
                return;
            }

            container.innerHTML = '<div class="text-center py-6 text-slate-400 text-sm"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Mencari...</div>';

            fetch(`{{ route('archives.search') }}?q=${encodeURIComponent(query)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.results.length === 0) {
                    container.innerHTML = '<div class="text-center py-8 text-slate-400 text-sm">Tidak ada dokumen yang sesuai dengan kata kunci.</div>';
                    return;
                }

                let html = '';
                data.results.forEach(item => {
                    html += `
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 hover:bg-slate-100/80 rounded-2xl transition border border-slate-100">
                            <a href="${item.show_url}" class="flex items-center gap-3.5 min-w-0 flex-1">
                                <div class="w-10 h-10 rounded-xl bg-[#ee3e38] text-white flex items-center justify-center font-black text-[10px] flex-shrink-0 shadow-xs">
                                    ${item.type}
                                </div>
                                <div class="truncate">
                                    <h5 class="text-sm font-bold text-slate-800 truncate">${item.judul}</h5>
                                    <p class="text-xs text-slate-500">${item.folder} &bull; ${item.tanggal}</p>
                                </div>
                            </a>
                            <a href="${item.download_url}" class="w-9 h-9 rounded-xl text-slate-400 hover:text-[#005596] hover:bg-white flex items-center justify-center transition ml-2 flex-shrink-0">
                                <i class="fa-solid fa-download text-sm"></i>
                            </a>
                        </div>
                    `;
                });
                container.innerHTML = html;
            })
            .catch(() => {
                container.innerHTML = '<div class="text-center py-6 text-rose-500 text-sm">Terjadi kesalahan saat memuat data.</div>';
            });
        }
    </script>
</body>
</html>
