<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier | MOEZ RESTO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;800&family=Playfair+Display:ital,wght@0,900;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            min-height: 100vh;
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
        }
        .btn-checkout {
            background: linear-gradient(135deg, #007cf0 0%, #00dfd8 100%);
            transition: 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .btn-checkout:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 124, 240, 0.3);
        }
    </style>
</head>
<body class="py-20 px-6">

    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-12">
            <a href="{{ url('/') }}" class="text-gray-400 hover:text-white transition flex items-center gap-2 text-xs uppercase tracking-widest font-bold">
                <i class="fas fa-arrow-left"></i> Retour au Menu
            </a>
            <h1 class="text-4xl font-luxury italic text-white">Mon <span class="text-blue-gradient not-italic font-black">Panier</span></h1>
            <div class="w-20"></div> 
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/20 text-green-500 p-4 rounded-2xl mb-8 text-center text-[10px] uppercase tracking-widest font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-6">
                @php $total = 0; @endphp
                @forelse($cartItems as $id => $details)
                    @php $total += $details['price'] * $details['quantity']; @endphp
                    <div class="glass-blue p-6 rounded-[32px] flex flex-col md:flex-row items-center gap-6 relative group border-white/5 hover:border-blue-500/30 transition-all">
                        
                        <div class="w-24 h-24 rounded-2xl overflow-hidden border border-white/10 flex-shrink-0 shadow-2xl">
                            <img src="{{ asset('storage/' . $details['image']) }}" class="w-full h-full object-cover" alt="{{ $details['name'] }}">
                        </div>

                        <div class="flex-grow text-center md:text-left">
                            <h3 class="text-lg font-bold uppercase tracking-tight text-white mb-1">{{ $details['name'] }}</h3>
                            <p class="text-blue-400 font-black text-sm">{{ number_format($details['price'], 3) }} DT</p>
                        </div>

                        <div class="flex items-center bg-white/5 rounded-full border border-white/10 p-1">
                            <form action="{{ route('cart.update', $id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="{{ $details['quantity'] - 1 }}">
                                <button type="submit" class="w-8 h-8 flex items-center justify-center hover:text-red-400 transition" {{ $details['quantity'] <= 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-minus text-[10px]"></i>
                                </button>
                            </form>
                            
                            <span class="px-5 font-black text-sm text-white">{{ $details['quantity'] }}</span>
                            
                            <form action="{{ route('cart.update', $id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="{{ $details['quantity'] + 1 }}">
                                <button type="submit" class="w-8 h-8 flex items-center justify-center hover:text-green-400 transition">
                                    <i class="fas fa-plus text-[10px]"></i>
                                </button>
                            </form>
                        </div>

                        <div class="text-right min-w-[120px]">
                            <p class="text-[9px] uppercase text-gray-500 font-bold mb-1 tracking-widest">Sous-Total</p>
                            <p class="font-black text-white italic text-lg">{{ number_format($details['price'] * $details['quantity'], 3) }} DT</p>
                        </div>

                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-10 h-10 rounded-full flex items-center justify-center text-gray-600 hover:bg-red-500/20 hover:text-red-500 transition-all">
                                <i class="fas fa-trash-can text-sm"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="glass-blue p-24 rounded-[48px] text-center border-dashed border-white/10">
                        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-shopping-basket text-3xl text-gray-700"></i>
                        </div>
                        <p class="text-gray-500 uppercase tracking-[5px] text-[10px] italic font-bold">Votre panier est vide</p>
                        <a href="{{ url('/') }}#menu" class="inline-block mt-8 px-8 py-3 bg-white/5 rounded-full text-blue-400 font-black uppercase text-[10px] tracking-widest border border-blue-400/20 hover:bg-blue-400 hover:text-white transition-all">
                            Découvrir la carte
                        </a>
                    </div>
                @endforelse
            </div>

            @if(count($cartItems) > 0)
            <div class="lg:col-span-1">
                <div class="glass-blue p-8 rounded-[40px] sticky top-32">
                    <h2 class="text-xl font-bold uppercase tracking-widest text-white mb-8 border-b border-white/5 pb-4 italic">Résumé <span class="text-blue-500">Moez Resto</span></h2>
                    
                    <div class="space-y-5 mb-8">
                        <div class="flex justify-between text-[11px] uppercase tracking-widest">
                            <span class="text-gray-500">Articles</span>
                            <span class="text-white font-bold">{{ count($cartItems) }}</span>
                        </div>
                        <div class="flex justify-between text-[11px] uppercase tracking-widest">
                            <span class="text-gray-500">Livraison</span>
                            <span class="text-green-400 font-black text-[10px] bg-green-500/10 px-2 py-1 rounded">Gratuite</span>
                        </div>
                        <div class="h-[1px] bg-white/5 my-4"></div>
                        <div class="flex justify-between items-end">
                            <span class="text-[10px] uppercase tracking-[4px] font-black text-gray-400">Total Final</span>
                            <span class="text-3xl font-black text-blue-gradient italic">{{ number_format($total, 3) }} DT</span>
                        </div>
                    </div>

                    <div class="relative mb-8">
                        <input type="text" placeholder="CODE PROMO" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-[10px] font-bold tracking-widest outline-none focus:border-blue-500 transition text-white uppercase">
                        <button class="absolute right-4 top-1/2 -translate-y-1/2 text-blue-400 font-black text-[10px] uppercase hover:text-white transition">Appliquer</button>
                    </div>

                    <a href="{{ route('cart.checkout_form') }}" class="btn-checkout block w-full py-5 rounded-2xl text-[11px] font-black text-center uppercase tracking-[3px] text-white shadow-xl">
                        Continuer vers la livraison <i class="fas fa-chevron-right ml-2 text-[10px]"></i>
                    </a>
                    
                    <div class="mt-8 flex items-center justify-center gap-4 text-gray-600">
                        <i class="fab fa-cc-visa text-xl"></i>
                        <i class="fab fa-cc-mastercard text-xl"></i>
                        <i class="fas fa-shield-halved text-lg"></i>
                    </div>

                    <p class="text-[8px] text-gray-700 text-center mt-6 uppercase tracking-[3px] font-bold">
                        Paiement sécurisé — Moez Resto © 2026
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>

</body>
</html>