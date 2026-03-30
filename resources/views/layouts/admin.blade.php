<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ADMIN • MOEZ RESTO')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --navy: #020617; 
            --accent: #007cf0; 
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.05);
        }

        body { 
            background-color: var(--navy); 
            color: #e2e8f0; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        /* Sidebar Glassmorphism */
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: rgba(3, 7, 18, 0.9);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(0, 124, 240, 0.1);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            padding: 2.5rem 1.5rem;
        }

        .main-content {
            margin-left: 280px;
            padding: 2.5rem 3.5rem;
            min-height: 100vh;
        }

        /* Navigation Links */
        .nav-link {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            margin-bottom: 6px;
            border-radius: 16px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            border: 1px solid transparent;
        }

        .nav-link i { 
            margin-right: 15px; 
            font-size: 18px; 
            width: 24px; 
            text-align: center; 
            transition: 0.3s;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.03);
            color: #fff;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(0, 124, 240, 0.1);
            color: var(--accent);
            border: 1px solid rgba(0, 124, 240, 0.2);
            box-shadow: 0 10px 20px -10px rgba(0, 124, 240, 0.3);
        }

        .nav-link.active i {
            color: var(--accent);
            filter: drop-shadow(0 0 8px rgba(0, 124, 240, 0.5));
        }

        .btn-logout {
            margin-top: auto;
            background: rgba(239, 68, 68, 0.05) !important;
            color: #ef4444 !important;
            border: 1px solid rgba(239, 68, 68, 0.1) !important;
        }

        .btn-logout:hover {
            background: #ef4444 !important;
            color: white !important;
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2);
        }

        .text-blue-gradient {
            background: linear-gradient(to right, #007cf0, #00dfd8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Notifications */
        .alert-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 2000;
            padding: 15px 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--navy); }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="mb-14 px-4">
            <h1 class="text-2xl font-black italic tracking-tighter">MOEZ<span class="text-[#007cf0]">.</span> ADMIN</h1>
            <p class="text-[9px] uppercase tracking-[5px] text-gray-600 mt-2 font-black">Control Panel 2026</p>
        </div>

        <nav class="flex-1 overflow-y-auto pr-2">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Vue d'ensemble
            </a>

            <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i> Commandes
            </a>

            <a href="{{ route('admin.dishes') }}" class="nav-link {{ request()->routeIs('admin.dishes') ? 'active' : '' }}">
                <i class="fa-solid fa-utensils"></i> Les Plats
            </a>

            <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i> Catégories
            </a>

            <a href="{{ route('admin.offers') }}" class="nav-link {{ request()->routeIs('admin.offers') ? 'active' : '' }}">
                <i class="fa-solid fa-fire"></i> Offres
            </a>

            <a href="{{ route('admin.revenus') }}" class="nav-link {{ request()->routeIs('admin.revenus') ? 'active' : '' }}">
                <i class="fa-solid fa-wallet"></i> Revenus
            </a>

            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fa-solid fa-users-gear"></i> Utilisateurs
            </a>
            
            <div class="h-[1px] bg-white/5 my-8 mx-4"></div>
            
            <a href="{{ url('/') }}" class="nav-link" target="_blank">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Voir le site
            </a>
        </nav>

        <form action="{{ route('logout') }}" method="POST" class="mt-auto">
            @csrf
            <button type="submit" class="nav-link btn-logout w-full text-left font-black cursor-pointer flex items-center">
                <i class="fa-solid fa-power-off"></i> Déconnexion
            </button>
        </form>
    </aside>

    <main class="main-content">
        <header class="flex justify-between items-center mb-16">
            <div>
                <h2 class="text-[10px] uppercase tracking-[6px] text-gray-500 font-black mb-1">Système Administrateur</h2>
                <h3 class="text-4xl font-black italic">Salut, <span class="text-blue-gradient">{{ Auth::user()->name }}</span></h3>
            </div>
            
            <div class="flex items-center space-x-5">
                <div class="text-right hidden md:block">
                    <p class="text-xs font-black text-white uppercase tracking-widest">{{ Auth::user()->name }}</p>
                    <p class="text-[9px] text-[#007cf0] uppercase tracking-[4px] font-bold mt-1">Super Administrateur</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#007cf0] to-[#00dfd8] flex items-center justify-center font-black text-black text-2xl shadow-[0_15px_30px_rgba(0,124,240,0.3)] transform rotate-3 hover:rotate-0 transition-transform duration-300">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        @if(session('success'))
            <div class="alert-toast bg-green-500/20 border border-green-500/50 text-green-400">
                <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-toast bg-red-500/20 border border-red-500/50 text-red-400">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
            @yield('content')
        </div>
    </main>

    <script>
        // Auto-hide notifications après 4 secondes
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-toast');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = '0.5s';
                setTimeout(() => alert.remove(), 500);
            });
        }, 4000);
    </script>
</body>
</html>