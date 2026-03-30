<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOEZ RESTO | Expérience Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;800&family=Playfair+Display:ital,wght@0,900;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/scrollreveal"></script>
    
    <style>
        :root { 
            --deep-navy: #020617; 
            --electric-blue: #007cf0; 
            --cyan: #00dfd8;
        }
        body { 
            background-color: var(--deep-navy); 
            color: white; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-image: radial-gradient(circle at 50% -20%, rgba(0, 124, 240, 0.15) 0%, transparent 50%);
        }
        .font-luxury { font-family: 'Playfair Display', serif; }
        .text-blue-gradient {
            background: linear-gradient(to right, #007cf0, #00dfd8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glass-blue {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 124, 240, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        .btn-blue {
            background: linear-gradient(135deg, #007cf0 0%, #00dfd8 100%);
            padding: 12px 35px;
            border-radius: 50px;
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            transition: 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            color: white;
            display: inline-block;
            cursor: pointer;
            border: none;
        }
        .btn-blue:hover {
            transform: scale(1.05) translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 124, 240, 0.4);
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .search-container input::placeholder { color: rgba(255,255,255,0.3); }

        /* Modal overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(2, 6, 23, 0.9);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: none;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }
        .modal-card {
            max-width: 900px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            background: rgba(10, 20, 40, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 2rem;
            border: 1px solid rgba(0, 124, 240, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }
        .modal-overlay.active .modal-card {
            transform: scale(1);
        }
        .modal-card::-webkit-scrollbar {
            width: 4px;
        }
        .modal-card::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }
        .modal-card::-webkit-scrollbar-thumb {
            background: #007cf0;
            border-radius: 10px;
        }
    </style>
</head>
<body class="overflow-x-hidden">

    <!-- Navigation (identique mais avec icône panier dynamique) -->
    <nav class="fixed w-full z-[100] px-8 py-4 flex justify-between items-center glass-blue rounded-full mt-4 max-w-7xl mx-auto left-4 right-4 border-white/5">
        <div class="flex items-center gap-4">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto group-hover:rotate-12 transition-transform duration-500">
                <div class="text-2xl font-luxury font-black tracking-tighter italic">
                    MOEZ<span class="text-blue-gradient">.</span>
                </div>
            </a>
        </div>
        
        <div class="hidden lg:flex space-x-10 text-[10px] uppercase tracking-[3px] font-bold">
            <a href="#offres" class="hover:text-[#007cf0] transition">Privilèges</a>
            <a href="#menu" class="hover:text-[#007cf0] transition">La Carte</a>
            <a href="#reservations" class="hover:text-[#007cf0] transition">Réservations</a>
        </div>

        <div class="flex items-center space-x-6">
            @auth
                <a href="{{ route('cart.index') }}" class="relative w-10 h-10 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 hover:bg-blue-500 hover:text-white transition flex items-center justify-center group">
                    <i class="fa-solid fa-cart-shopping text-xs"></i>
                    <span class="absolute -top-1 -right-1 bg-blue-600 text-[8px] font-black w-4 h-4 rounded-full flex items-center justify-center text-white border border-[#020617] group-hover:scale-110 transition">
                        {{ session('cart') ? count(session('cart')) : 0 }}
                    </span>
                </a>

                <div class="flex items-center space-x-4 border-l border-white/10 pl-6">
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-[9px] font-black uppercase tracking-widest bg-white/10 border border-blue-500/30 px-3 py-2 rounded-lg text-blue-400 hover:bg-blue-500 hover:text-white transition">Admin</a>
                    @endif
                    <div class="flex flex-col items-end">
                        <span class="text-[9px] uppercase tracking-widest text-gray-500 font-bold">Bienvenue</span>
                        <span class="text-xs font-black text-white uppercase italic">{{ Auth::user()->name }}</span>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-full bg-red-500/10 border border-red-500/20 text-red-500 hover:bg-red-500 hover:text-white transition flex items-center justify-center cursor-pointer">
                            <i class="fa-solid fa-power-off text-xs"></i>
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-blue">Connexion</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="h-screen flex flex-col items-center justify-center text-center px-6 relative">
        <div class="absolute inset-0 z-[-1]">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#020617]"></div>
            <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=1974&auto=format&fit=crop" class="w-full h-full object-cover opacity-20 grayscale" alt="Restaurant Background">
        </div>
        
        @if(session('success'))
            <div class="fixed top-24 left-1/2 -translate-x-1/2 z-[110] bg-blue-600 text-white px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-widest shadow-2xl animate-bounce">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="text-[10px] uppercase tracking-[1.5em] text-blue-400 mb-8 reveal-top">L'Excellence Culinaire par Moez</h2>
        <h1 class="text-7xl md:text-[10rem] font-luxury leading-none mb-12 reveal-top italic">
            Pure <span class="text-blue-gradient not-italic font-black">Gastronomie</span>
        </h1>
        <div class="flex gap-6 reveal-bottom">
            <a href="#menu" class="btn-blue">Voir la Carte</a>
            <a href="#reservations" class="px-8 py-3 border border-white/10 rounded-full text-[11px] uppercase tracking-widest hover:bg-white hover:text-black transition duration-500">Réserver une table</a>
        </div>
    </section>

    <!-- Offres Exclusives -->
    <section id="offres" class="py-32">
        <div class="max-w-7xl mx-auto px-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-20 reveal-left">
                <h3 class="text-5xl font-luxury italic text-white leading-tight">Offres <br><span class="text-blue-gradient not-italic font-black">Exclusives</span></h3>
                <p class="text-gray-500 text-xs uppercase tracking-widest max-w-xs text-right italic">Privilèges réservés à nos clients en Tunisie pour l'année 2026.</p>
            </div>
            
            <div class="flex space-x-8 overflow-x-auto pb-10 no-scrollbar">
                @forelse($offers as $offer)
                <div class="min-w-[350px] glass-blue p-10 rounded-[32px] group hover:border-[#007cf0] transition-all duration-500 reveal-bottom relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#007cf0]/5 blur-[50px] rounded-full"></div>
                    <span class="text-[9px] font-bold text-[#007cf0] uppercase tracking-[4px] block mb-6 italic">Promo Active</span>
                    <div class="text-6xl font-luxury text-white mb-4 group-hover:scale-110 transition duration-500">
                        @if($offer->type == 'percentage') -{{ round($offer->value) }}% @else -{{ number_format($offer->value, 3) }} <span class="text-xs italic">DT</span> @endif
                    </div>
                    <h4 class="text-sm font-bold uppercase tracking-widest mb-8 text-white">{{ $offer->name }}</h4>
                    <div class="bg-white/5 py-4 rounded-xl text-center font-mono text-blue-400 tracking-[8px] border border-white/5 group-hover:bg-blue-500/10 transition">
                        {{ $offer->code ?? 'MOEZ26' }}
                    </div>
                </div>
                @empty
                <div class="w-full glass-blue p-20 rounded-[32px] text-center border-dashed border-white/10">
                    <p class="text-gray-600 uppercase tracking-widest text-xs italic">Aucune offre exclusive actuellement.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Menu avec recherche et détail des plats -->
    <section id="menu" class="py-32 bg-[#030712]/50">
        <div class="max-w-7xl mx-auto px-10">
            
            <!-- Barre de recherche -->
            <div class="mb-24 reveal-top">
                <div class="max-w-2xl mx-auto relative search-container">
                    <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-blue-400 text-sm"></i>
                    </div>
                    <form action="{{ route('menu.search') }}" method="GET">
                        <input type="text" name="query" value="{{ request('query') }}" placeholder="RECHERCHER UN PLAT, UNE SAVEUR..." 
                        class="w-full bg-white/5 border border-white/10 rounded-full py-6 pl-16 pr-32 text-[10px] font-bold tracking-[3px] uppercase text-white focus:outline-none focus:border-blue-500/50 focus:bg-white/10 transition-all duration-500 shadow-2xl">
                        <button type="submit" class="absolute right-3 top-3 bottom-3 px-8 rounded-full bg-blue-600 text-[9px] font-black uppercase tracking-widest hover:bg-cyan-500 transition-colors duration-300">
                            Trouver
                        </button>
                    </form>
                </div>
            </div>

            @if(request('query'))
                <div class="text-center mb-16 reveal-top">
                    <p class="text-gray-500 uppercase tracking-widest text-[10px] mb-2">Résultats pour votre recherche :</p>
                    <h4 class="text-3xl font-luxury italic text-blue-400">"{{ request('query') }}"</h4>
                    <a href="{{ route('index') }}" class="text-[9px] text-white/30 hover:text-white transition uppercase tracking-[3px] mt-6 inline-block border-b border-white/10">Réinitialiser la carte</a>
                </div>
            @endif

            <h3 class="text-7xl font-luxury text-center mb-32 reveal-top italic font-black text-white">La <span class="text-blue-gradient not-italic">Carte</span></h3>
            
            @forelse($categories as $category)
            <div class="mb-32">
                <div class="flex items-center mb-16 reveal-left">
                    <h4 class="text-xs uppercase tracking-[1em] text-blue-500 italic font-black border-l-4 border-blue-500 pl-6">{{ $category->name }}</h4>
                    <div class="flex-grow h-[1px] bg-gradient-to-r from-blue-500/20 to-transparent ml-10"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($category->dishes as $dish)
                    @php
                        $final_price = $dish->price;
                        $has_offer = false;
                        if($dish->offer) {
                            $has_offer = true;
                            if($dish->offer->type == 'percentage') {
                                $final_price = $dish->price - ($dish->price * ($dish->offer->value / 100));
                            } else {
                                $final_price = $dish->price - $dish->offer->value;
                            }
                        }
                    @endphp
                    <div class="glass-blue p-6 rounded-[32px] group hover:bg-white/5 transition duration-500 reveal-bottom relative border-white/5 cursor-pointer" onclick="openModal({{ $dish->id }})">
                        <!-- Badge offre -->
                        @if($has_offer)
                        <div class="absolute top-8 left-8 z-10 bg-blue-600 text-[8px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg animate-pulse">
                            -@if($dish->offer->type == 'percentage') {{ round($dish->offer->value) }}% @else PROMO @endif
                        </div>
                        @endif

                        <!-- Bouton ajouter au panier (empêche la propagation du click sur le modal) -->
                        <form action="{{ route('cart.add', $dish->id) }}" method="POST" onclick="event.stopPropagation()">
                            @csrf
                            <button type="submit" class="absolute top-8 right-8 z-20 w-10 h-10 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white hover:bg-blue-600 hover:border-blue-600 transition duration-500 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 flex items-center justify-center">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </form>

                        <div class="overflow-hidden rounded-[24px] h-72 mb-6 border border-white/5">
                            <img src="{{ asset('storage/'.$dish->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 brightness-90 group-hover:brightness-100" alt="{{ $dish->name }}">
                        </div>
                        
                        <div class="px-2">
                            <h5 class="text-lg font-bold tracking-tight uppercase text-white mb-2">{{ $dish->name }}</h5>
                            
                            <div class="flex items-center gap-3 mb-4">
                                @if($has_offer)
                                    <span class="text-[#007cf0] font-black tracking-widest text-lg italic">{{ number_format($final_price, 3) }} DT</span>
                                    <span class="text-gray-600 line-through text-[10px] font-bold">{{ number_format($dish->price, 3) }} DT</span>
                                @else
                                    <span class="text-[#007cf0] font-black tracking-widest text-lg italic">{{ number_format($dish->price, 3) }} DT</span>
                                @endif
                            </div>
                        </div>
                        
                        <p class="text-gray-500 text-[11px] leading-relaxed uppercase tracking-wider px-2 italic line-clamp-2">
                            {{ $dish->description ?? 'Une création culinaire artisanale signée Moez Resto.' }}
                        </p>
                    </div>

                    <!-- Modal pour ce plat (caché par défaut) -->
                    <div id="modal-{{ $dish->id }}" class="modal-overlay" onclick="closeModal({{ $dish->id }})">
                        <div class="modal-card" onclick="event.stopPropagation()">
                            <div class="relative">
                                <button onclick="closeModal({{ $dish->id }})" class="absolute top-6 right-6 text-white/70 hover:text-white text-2xl z-10 bg-black/30 rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm transition">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                                <div class="h-80 md:h-96 overflow-hidden rounded-t-2xl">
                                    <img src="{{ asset('storage/'.$dish->image) }}" class="w-full h-full object-cover" alt="{{ $dish->name }}">
                                </div>
                                <div class="p-8 md:p-12">
                                    <div class="flex justify-between items-start mb-6">
                                        <h2 class="text-3xl md:text-4xl font-luxury italic text-white">{{ $dish->name }}</h2>
                                        <div class="text-right">
                                            @if($has_offer)
                                                <span class="text-2xl font-black text-blue-400">{{ number_format($final_price, 3) }} DT</span>
                                                <span class="block text-sm line-through text-gray-500">{{ number_format($dish->price, 3) }} DT</span>
                                            @else
                                                <span class="text-2xl font-black text-blue-400">{{ number_format($dish->price, 3) }} DT</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-6">
                                        <span class="text-[10px] uppercase tracking-widest text-blue-400 border-l-2 border-blue-500 pl-3">Description</span>
                                        <p class="text-gray-300 text-sm leading-relaxed mt-3">{{ $dish->description ?? 'Savourez cette création exceptionnelle, élaborée avec des produits frais et une passion inégalée pour la gastronomie.' }}</p>
                                    </div>
                                    @if($dish->ingredients)
                                    <div class="mb-6">
                                        <span class="text-[10px] uppercase tracking-widest text-blue-400 border-l-2 border-blue-500 pl-3">Ingrédients</span>
                                        <p class="text-gray-300 text-sm mt-2">{{ $dish->ingredients }}</p>
                                    </div>
                                    @endif
                                    @if($dish->allergens)
                                    <div class="mb-8">
                                        <span class="text-[10px] uppercase tracking-widest text-amber-400 border-l-2 border-amber-500 pl-3">Allergènes</span>
                                        <p class="text-gray-400 text-xs mt-2">{{ $dish->allergens }}</p>
                                    </div>
                                    @endif
                                    <div class="flex flex-wrap gap-4">
                                        <form action="{{ route('cart.add', $dish->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-blue px-8 py-3 text-xs">
                                                <i class="fa-solid fa-cart-plus mr-2"></i> Ajouter au panier
                                            </button>
                                        </form>
                                        <button onclick="closeModal({{ $dish->id }})" class="border border-white/20 px-8 py-3 rounded-full text-xs uppercase tracking-widest hover:bg-white/10 transition">
                                            Fermer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="text-center py-20">
                <p class="text-gray-600 uppercase tracking-widest text-xs italic">Aucun plat ne correspond à votre recherche.</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Section Réservation -->
    <section id="reservations" class="py-40 relative">
        <div class="max-w-4xl mx-auto px-10">
            <div class="glass-blue p-16 rounded-[48px] border-white/10 relative overflow-hidden reveal-bottom shadow-2xl">
                <div class="text-center mb-16">
                    <h3 class="text-5xl font-luxury italic mb-4 text-white leading-none">Réserver <span class="text-blue-gradient not-italic font-black">une Table</span></h3>
                    <p class="text-[10px] uppercase tracking-[6px] text-gray-500 italic font-bold">Vivez l'immersion gastronomique à Tunis</p>
                </div>

                <form action="{{ route('reservations.store') }}" method="POST" class="space-y-12">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-12">
                        <div class="space-y-4">
                            <label class="text-[9px] uppercase tracking-widest text-blue-400 font-black ml-2">Nombre de Convives</label>
                            <input type="number" name="guest_count" min="1" max="20" required class="w-full bg-white/5 border border-white/10 rounded-2xl py-5 px-6 text-white focus:border-[#007cf0] outline-none transition">
                        </div>
                        <div class="space-y-4">
                            <label class="text-[9px] uppercase tracking-widest text-blue-400 font-black ml-2">Date & Heure</label>
                            <input type="datetime-local" name="reservation_date" required class="w-full bg-white/5 border border-white/10 rounded-2xl py-5 px-6 text-white focus:border-[#007cf0] outline-none transition [color-scheme:dark]">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[9px] uppercase tracking-widest text-blue-400 font-black ml-2">Notes Particulières</label>
                        <textarea name="special_requests" rows="2" class="w-full bg-white/5 border border-white/10 rounded-2xl py-5 px-6 text-white focus:border-[#007cf0] outline-none transition placeholder:text-gray-700" placeholder="Allergies, Célébrations..."></textarea>
                    </div>
                    <button type="submit" class="btn-blue w-full py-6 text-sm shadow-2xl">Confirmer ma demande</button>
                </form>
            </div>
        </div>
    </section>

    <footer class="py-20 border-t border-white/5 text-center relative overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-96 bg-blue-500/5 blur-[120px] rounded-full"></div>
        <div class="text-3xl font-luxury italic font-black mb-6 tracking-tighter text-white">MOEZ<span class="text-blue-gradient">.</span></div>
        <p class="text-[9px] uppercase tracking-[1em] text-gray-500 mb-6 italic">Créateur d'Émotions Culinaire — 2026</p>
        <p class="text-[8px] text-gray-800 uppercase tracking-widest font-bold">© {{ date('Y') }} Moez Ben Khelil — Tunis, Tunisie</p>
    </footer>

    <script>
        // ScrollReveal initialisation
        ScrollReveal().reveal('.reveal-top', { origin: 'top', distance: '100px', duration: 1500, delay: 200 });
        ScrollReveal().reveal('.reveal-bottom', { origin: 'bottom', distance: '100px', duration: 1500, delay: 200 });
        ScrollReveal().reveal('.reveal-left', { origin: 'left', distance: '100px', duration: 1500, delay: 200 });

        // Fonctions pour ouvrir/fermer le modal
        function openModal(dishId) {
            const modal = document.getElementById(`modal-${dishId}`);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden'; // Empêche le scroll arrière
            }
        }

        function closeModal(dishId) {
            const modal = document.getElementById(`modal-${dishId}`);
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        // Fermeture avec la touche Echap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const activeModals = document.querySelectorAll('.modal-overlay.active');
                activeModals.forEach(modal => {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
        });
    </script>
</body>
</html>