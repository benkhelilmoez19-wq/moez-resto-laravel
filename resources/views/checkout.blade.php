<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement | MOEZ RESTO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { --deep-navy: #020617; --electric-blue: #007cf0; }
        body { 
            background-color: var(--deep-navy); 
            color: white; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-image: radial-gradient(circle at 50% -20%, rgba(0, 124, 240, 0.15) 0%, transparent 50%);
            min-height: 100vh;
        }
        .glass-blue {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 124, 240, 0.2);
        }
        /* Style pour les options de paiement */
        .payment-option input:checked + .option-card {
            border-color: var(--electric-blue);
            background: rgba(0, 124, 240, 0.1);
            box-shadow: 0 0 20px rgba(0, 124, 240, 0.2);
        }
        .payment-option input:checked + .option-card i { color: var(--electric-blue); }
        
        input, textarea {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }
    </style>
</head>
<body class="py-16 px-6">

    <div class="max-w-5xl mx-auto">
        <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-white transition text-[10px] uppercase tracking-[3px] font-bold mb-10">
            <i class="fas fa-arrow-left"></i> Retour au panier
        </a>

        <form action="{{ route('cart.confirm') }}" method="POST">
            @csrf
            <div class="grid lg:grid-cols-3 gap-12">
                
                <div class="lg:col-span-2 space-y-8">
                    <div>
                        <h1 class="text-3xl font-black uppercase tracking-tighter mb-2 italic">Finaliser la <span class="text-blue-500">Commande</span></h1>
                        <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-8">Informations de livraison et paiement</p>
                    </div>

                    <div class="glass-blue p-8 rounded-[40px] space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] uppercase tracking-widest font-black text-gray-400 mb-3 ml-2">Nom Complet</label>
                                <input type="text" name="customer_name" value="{{ Auth::user()->name }}" required class="w-full py-4 px-6 rounded-2xl font-bold text-sm outline-none focus:border-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase tracking-widest font-black text-gray-400 mb-3 ml-2">Téléphone</label>
                                <input type="tel" name="phone" placeholder="21 000 000" required class="w-full py-4 px-6 rounded-2xl font-bold text-sm outline-none focus:border-blue-500 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase tracking-widest font-black text-gray-400 mb-3 ml-2">Adresse de livraison</label>
                            <textarea name="address" rows="2" placeholder="Rue, Résidence, Appartement, Ville..." required class="w-full py-4 px-6 rounded-2xl font-bold text-sm resize-none outline-none focus:border-blue-500 transition-all"></textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase tracking-widest font-black text-gray-400 mb-4 ml-2">Mode de paiement</label>
                            <div class="grid md:grid-cols-2 gap-4">
                                
                                <label class="payment-option cursor-pointer">
                                    <input type="radio" name="payment_method" value="cash" class="hidden" checked>
                                    <div class="option-card glass-blue p-5 rounded-3xl border-white/5 flex items-center gap-4 transition-all">
                                        <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-xl">
                                            <i class="fas fa-money-bill-wave text-gray-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold">Espèces</p>
                                            <p class="text-[9px] text-gray-500 uppercase tracking-tighter">À la livraison</p>
                                        </div>
                                    </div>
                                </label>

                                <label class="payment-option cursor-pointer">
                                    <input type="radio" name="payment_method" value="card" class="hidden">
                                    <div class="option-card glass-blue p-5 rounded-3xl border-white/5 flex items-center gap-4 transition-all">
                                        <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-xl">
                                            <i class="fas fa-credit-card text-gray-400"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold">Carte Bancaire</p>
                                            <p class="text-[9px] text-gray-500 uppercase tracking-tighter">Paiement en ligne</p>
                                        </div>
                                    </div>
                                </label>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="glass-blue p-8 rounded-[40px] sticky top-8">
                        <h2 class="text-xs uppercase tracking-[4px] font-black text-blue-400 mb-8 border-b border-white/5 pb-4">Résumé</h2>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-[11px] uppercase tracking-widest">
                                <span class="text-gray-500">Sous-total</span>
                                <span class="text-white font-bold">{{ number_format($total, 3) }} DT</span>
                            </div>
                            <div class="flex justify-between text-[11px] uppercase tracking-widest">
                                <span class="text-gray-500">Livraison</span>
                                <span class="text-green-400 font-bold italic">Gratuite</span>
                            </div>
                            <div class="h-[1px] bg-white/5 my-4"></div>
                            <div class="flex justify-between items-end">
                                <span class="text-xs uppercase font-black">Total</span>
                                <span class="text-2xl font-black text-blue-500 italic">{{ number_format($total, 3) }} DT</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-5 rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 font-black uppercase tracking-[3px] text-[10px] shadow-2xl hover:scale-105 transition-transform active:scale-95">
                            Confirmer & Payer <i class="fas fa-shield-check ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

</body>
</html>