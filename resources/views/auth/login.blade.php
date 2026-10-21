<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">N
    <title>Login - Sistem Pengarsipan Dokumen PLN</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .pln-bg-gradient {
            background: linear-gradient(135deg, #10707a 0%, #1e578a 25%, #186899 50%, #1c5382 75%, #0f466b 100%);
            position: relative;
            overflow: hidden;
        }
        /* SUTET and geometric lines backdrop */
        .tower-overlay {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(115deg, rgba(20, 184, 166, 0.15) 0%, transparent 40%),
                linear-gradient(65deg, rgba(2, 132, 199, 0.25) 0%, transparent 60%),
                linear-gradient(160deg, rgba(14, 116, 144, 0.3) 0%, transparent 70%);
            pointer-events: none;
        }
        .diagonal-stripe-1 {
            position: absolute;
            top: -10%;
            left: 10%;
            width: 250px;
            height: 140%;
            background: linear-gradient(to bottom, rgba(56, 189, 248, 0.12), rgba(16, 185, 129, 0.08));
            transform: rotate(18deg);
            pointer-events: none;
        }
        .diagonal-stripe-2 {
            position: absolute;
            top: -10%;
            left: 28%;
            width: 320px;
            height: 140%;
            background: linear-gradient(to bottom, rgba(2, 132, 199, 0.15), rgba(14, 116, 144, 0.1));
            transform: rotate(18deg);
            pointer-events: none;
        }
        .diagonal-stripe-3 {
            position: absolute;
            top: -10%;
            left: 48%;
            width: 280px;
            height: 140%;
            background: linear-gradient(to bottom, rgba(3, 105, 161, 0.2), rgba(7, 89, 133, 0.15));
            transform: rotate(18deg);
            pointer-events: none;
        }
    </style>
</head>
<body class="h-full pln-bg-gradient flex flex-col justify-between text-white relative select-none">

    <!-- Decorative angled bands -->
    <div class="tower-overlay"></div>
    <div class="diagonal-stripe-1"></div>
    <div class="diagonal-stripe-2"></div>
    <div class="diagonal-stripe-3"></div>

    <!-- Power Transmission Pylon SVG Background Illustration -->
    <div class="absolute inset-0 flex items-center justify-center opacity-15 pointer-events-none overflow-hidden">
        <svg class="w-[1200px] h-[900px] text-white" viewBox="0 0 1000 800" fill="none" stroke="currentColor" stroke-width="1.5">
            <!-- Center SUTET Tower -->
            <path d="M500,100 L420,700 L580,700 Z" />
            <line x1="470" y1="180" x2="530" y2="180" />
            <line x1="450" y1="280" x2="550" y2="280" />
            <line x1="435" y1="400" x2="565" y2="400" />
            <line x1="425" y1="550" x2="575" y2="550" />
            <!-- Cross bracing -->
            <line x1="470" y1="180" x2="550" y2="280" /><line x1="530" y1="180" x2="450" y2="280" />
            <line x1="450" y1="280" x2="565" y2="400" /><line x1="550" y1="280" x2="435" y2="400" />
            <line x1="435" y1="400" x2="575" y2="550" /><line x1="565" y1="400" x2="425" y2="550" />
            <!-- Arms -->
            <line x1="320" y1="200" x2="680" y2="200" />
            <line x1="280" y1="300" x2="720" y2="300" />
            <line x1="340" y1="420" x2="660" y2="420" />
            <!-- Power Lines -->
            <path d="M0,280 Q500,420 1000,280" stroke-dasharray="4,4" />
            <path d="M0,380 Q500,520 1000,380" stroke-dasharray="4,4" />
        </svg>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 flex-1 flex items-center justify-between max-w-7xl w-full mx-auto px-8 sm:px-12 lg:px-16 py-12">
        
        <!-- Left Side: PLN Branding & Title -->
        <div class="max-w-xl pr-8">
            <!-- PLN Logo Badge -->
            <div class="flex items-center gap-3.5 mb-8">
                <div class="w-14 h-14 rounded-2xl bg-[#fac000] flex items-center justify-center shadow-lg relative overflow-hidden">
                    <svg class="w-9 h-9 text-red-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M13 2L3 14h7v8l11-13h-8l0-7z" fill="#dc2626"/>
                        <path d="M4 17h16v1.2H4zm0 3.2h16v1.2H4z" fill="#0284c7"/>
                    </svg>
                </div>
                <span class="text-4xl font-extrabold tracking-wider text-white">PLN</span>
            </div>

            <!-- Title & Subtitle -->
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight mb-3">
                Sistem Pengarsipan Dokumen
            </h1>
            <h2 class="text-2xl sm:text-3xl font-bold text-white/95 mb-6">
                Divisi Jaringan Konstruksi
            </h2>
            <p class="text-base sm:text-lg text-white/80 font-normal leading-relaxed max-w-md">
                Mudah, Cepat, dan Aman dalam mengelola arsip digital.
            </p>
        </div>

        <!-- Right Side: Login Card -->
        <div class="w-full max-w-md">
            <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-2xl text-slate-800 border border-white/20 backdrop-blur-sm">
                
                <div class="mb-8">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-2">Selamat Datang</h3>
                    <p class="text-sm text-slate-500 font-normal">Silakan login untuk melanjutkan ke sistem</p>
                </div>

                @if($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <!-- Username or Email -->
                    <div>
                        <label for="login" class="block text-xs font-bold text-slate-700 mb-2">
                            Username / Email
                        </label>
                        <input type="text" 
                               name="login" 
                               id="login" 
                               value="{{ old('login') }}" 
                               required 
                               placeholder="Masukkan username atau email"
                               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005596] focus:border-transparent transition">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-2">
                            Password
                        </label>
                        <div class="relative flex items-center">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   required 
                                   placeholder="Masukkan password"
                                   class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005596] focus:border-transparent transition pr-10">
                            <button type="button" 
                                    onclick="togglePasswordVisibility()" 
                                    class="absolute right-3 text-slate-400 hover:text-slate-600 transition">
                                <i id="eyeIcon" class="fa-regular fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-3.5 px-4 bg-[#fac000] hover:bg-[#e5af00] text-slate-900 font-bold text-sm rounded-xl shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center gap-2">
                            <span>Login</span>
                        </button>
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="text-center pt-2">
                        <a href="javascript:void(0)" onclick="alert('Silakan hubungi Administrator PLN untuk melakukan reset password akun Anda.')" class="text-xs font-semibold text-[#005ba4] hover:underline">
                            Lupa password?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bottom Footer -->
    <footer class="relative z-10 w-full text-right px-8 py-4 text-xs text-white/60">
        &copy; 2026 PLN
    </footer>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
