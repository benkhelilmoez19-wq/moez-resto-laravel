<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations | MOEZ RESTO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;800&family=Playfair+Display:ital,wght@0,900;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { --deep-navy: #020617; --electric-blue: #007cf0; --cyan: #00dfd8; }
        body { 
            background-color: var(--deep-navy); 
            color: white; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-image: radial-gradient(circle at 50% -20%, rgba(0, 124, 240, 0.1) 0%, transparent 50%);
        }
        .font-luxury { font-family: 'Playfair Display', serif; }
        .glass-blue {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 124, 240, 0.1);
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body class="min-h-screen pb-20">

    <nav class="p-8 flex justify-between items-center max-w-7xl mx-auto">
        <a href="{{ route('index') }}" class="text-2xl font-luxury font-black italic">
            MOEZ<span class="text-blue-500">.</span>
        </a>
        <a href="{{ route('index') }}" class="text-[10px] uppercase tracking-widest font-bold hover:text-blue-400 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Retour à l'accueil
        </a>
    </nav>

    <div class="max-w-5xl mx-auto px-6 mt-10">
        <header class="mb-16">
            <h1 class="text-5xl font-luxury italic mb-4">Mes <span class="text-blue-500 not-italic">Réservations</span></h1>
            <p class="text-gray-500 text-xs uppercase tracking-[4px]">Historique de vos expériences gastronomiques</p>
        </header>

        @if($reservations->isEmpty())
            <div class="glass-blue rounded-[32px] p-20 text-center border-dashed border-white/10">
                <i class="fa-solid fa-calendar-xmark text-4xl text-gray-700 mb-6"></i>
                <p class="text-gray-500 uppercase tracking-widest text-sm">Vous n'avez aucune réservation pour le moment.</p>
                <a href="{{ route('index') }}#reservations" class="inline-block mt-8 bg-blue-600 text-white px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-blue-500 transition">Réserver une table</a>
            </div>
        @else
            <div class="grid gap-6">
                @foreach($reservations as $res)
                    <div class="glass-blue rounded-[24px] p-8 flex flex-col md:flex-row justify-between items-center group hover:border-blue-500/30 transition-all duration-500">
                        
                        <div class="flex items-center gap-8">
                            <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex flex-col items-center justify-center border border-blue-500/20">
                                <span class="text-[10px] font-bold text-blue-400 uppercase">{{ \Carbon\Carbon::parse($res->reservation_date)->format('M') }}</span>
                                <span class="text-xl font-black">{{ \Carbon\Carbon::parse($res->reservation_date)->format('d') }}</span>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold uppercase tracking-tight mb-1">Table pour {{ $res->guest_count }} personnes</h3>
                                <p class="text-gray-500 text-xs italic">
                                    <i class="fa-regular fa-clock mr-1"></i> 
                                    {{ \Carbon\Carbon::parse($res->reservation_date)->format('H:i') }} — Tunis, Tunisie
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-10 mt-6 md:mt-0">
                            <div class="text-right hidden md:block">
                                <span class="block text-[8px] uppercase tracking-widest text-gray-600 mb-1 font-bold">Référence</span>
                                <span class="font-mono text-xs text-blue-400">#RES-{{ $res->id }}</span>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                @if($res->status == 'pending')
                                    <span class="status-badge bg-amber-500/10 text-amber-500 border border-amber-500/20">En attente</span>
                                @elseif($res->status == 'confirmed')
                                    <span class="status-badge bg-green-500/10 text-green-500 border border-green-500/20">Confirmé</span>
                                @elseif($res->status == 'completed')
                                    <span class="status-badge bg-blue-500/10 text-blue-500 border border-blue-500/20">Terminé</span>
                                @else
                                    <span class="status-badge bg-red-500/10 text-red-500 border border-red-500/20">Annulé</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-12 text-center">
                <p class="text-[9px] text-gray-600 uppercase tracking-widest italic">Besoin d'aide ? Contactez notre conciergerie au +216 71 XXX XXX</p>
            </div>
        @endif
    </div>

</body>
</html>