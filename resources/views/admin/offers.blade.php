@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-10 px-4">
    <div>
        <h2 class="text-3xl font-black italic uppercase tracking-tighter text-white">
            Promotions & <span class="text-blue-gradient">Offres</span>
        </h2>
        <p class="text-[10px] text-gray-500 uppercase tracking-[3px] mt-1 font-bold italic">Planification des campagnes (Start & End Date)</p>
    </div>

    <button onclick="openAddModal()" class="px-6 py-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest hover:bg-[#007cf0] hover:text-black transition shadow-lg shadow-blue-500/10">
        + Créer une Offre
    </button>
</div>

@if(session('success'))
    <div class="mx-4 mb-8 p-4 bg-green-500/10 border border-green-500/20 text-green-500 rounded-2xl text-[10px] uppercase font-black tracking-widest">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-4">
    @foreach($offers as $offer)
    <div class="group relative bg-[#0f172a]/40 border border-white/5 rounded-[32px] p-1 transition-all duration-500 hover:border-blue-500/30">
        <div class="relative bg-[#161e31] rounded-[30px] p-6 h-full flex flex-col">
            
            <div class="flex justify-between items-start mb-6">
                @php
                    $isExpired = \Carbon\Carbon::parse($offer->end_date)->isPast();
                    $isUpcoming = \Carbon\Carbon::parse($offer->start_date)->isFuture();
                @endphp
                <div class="flex flex-col gap-1">
                    <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-tighter {{ $offer->is_active && !$isExpired && !$isUpcoming ? 'bg-green-500/10 text-green-500 border border-green-500/20' : 'bg-red-500/10 text-red-500 border border-red-500/20' }}">
                        {{ $offer->is_active ? ($isExpired ? '○ Expiré' : ($isUpcoming ? '🕒 À venir' : '● Actif')) : '○ Inactif' }}
                    </span>
                </div>
                
                <div class="flex gap-2">
                    <button onclick='openEditModal({!! $offer->toJson() !!})' class="text-gray-500 hover:text-[#007cf0] transition"><i class="fa-solid fa-pen-to-square"></i></button>
                    <form action="{{ route('admin.offers.delete', $offer->id) }}" method="POST" onsubmit="return confirm('Supprimer cette offre ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-500 hover:text-red-500 transition"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            </div>

            <h3 class="text-xl font-black text-white uppercase italic mb-1 group-hover:text-[#00dfd8] transition">{{ $offer->name }}</h3>
            
            <div class="mb-4">
                <code class="text-[#007cf0] text-[10px] font-bold tracking-widest bg-white/5 px-2 py-1 rounded">
                    {{ $offer->code ?? 'SANS CODE' }}
                </code>
            </div>

            <div class="space-y-4 mt-auto">
                <div class="flex justify-between items-center p-4 bg-white/5 rounded-2xl border border-white/5">
                    <span class="text-[9px] text-gray-400 font-black uppercase italic">Réduction</span>
                    <span class="text-xl font-black text-white">
                        @if($offer->type == 'percentage')
                            -{{ number_format($offer->value, 0) }}%
                        @else
                            -{{ number_format($offer->value, 2) }} DT
                        @endif
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="flex flex-col gap-1">
                        <span class="text-[7px] text-gray-600 uppercase font-black tracking-widest">Début</span>
                        <span class="text-[9px] text-gray-400 font-bold italic">{{ \Carbon\Carbon::parse($offer->start_date)->format('d/m/y H:i') }}</span>
                    </div>
                    <div class="flex flex-col gap-1 text-right">
                        <span class="text-[7px] text-gray-600 uppercase font-black tracking-widest">Fin</span>
                        <span class="text-[9px] text-white font-bold italic">{{ \Carbon\Carbon::parse($offer->end_date)->format('d/m/y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div id="offerModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/95 backdrop-blur-xl" onclick="closeModal()"></div>
    
    <div class="relative w-full max-w-2xl bg-[#0f172a] p-10 rounded-[40px] border border-white/10 shadow-2xl overflow-y-auto max-h-[90vh]">
        <h3 id="modalTitle" class="text-2xl font-black uppercase italic text-white mb-8">Configurer <span class="text-blue-gradient">l'Offre</span></h3>
        
        <form id="offerForm" method="POST">
            @csrf
            <div id="methodField"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="text-[9px] font-black text-gray-500 uppercase ml-2 mb-2 block tracking-widest">Nom de l'offre</label>
                    <input type="text" name="name" id="off_name" required placeholder="EX: VIBE SUMMER" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white outline-none focus:border-[#007cf0] font-bold">
                </div>
                <div>
                    <label class="text-[9px] font-black text-gray-500 uppercase ml-2 mb-2 block tracking-widest">Code Promo</label>
                    <input type="text" name="code" id="off_code" placeholder="EX: SUMMER24" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-[#00dfd8] outline-none focus:border-[#007cf0] font-black uppercase">
                </div>
            </div>

            <div class="mb-6">
                <label class="text-[9px] font-black text-[#007cf0] uppercase ml-2 mb-2 block italic tracking-widest">Cibler un plat spécifique</label>
                <select name="dish_id" id="off_dish" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white font-bold outline-none cursor-pointer">
                    <option value="">✨ TOUT LE MENU (GLOBAL)</option>
                    @foreach($dishes as $dish)
                        <option value="{{ $dish->id }}">{{ strtoupper($dish->name) }} ({{ $dish->price }} DT)</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="flex gap-2">
                    <div class="w-1/3">
                        <label class="text-[9px] font-black text-gray-500 uppercase ml-2 mb-2 block">Type</label>
                        <select name="type" id="off_type" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-2 text-white text-[10px] font-black outline-none">
                            <option value="percentage">%</option>
                            <option value="fixed">DT</option>
                        </select>
                    </div>
                    <div class="w-2/3">
                        <label class="text-[9px] font-black text-gray-500 uppercase ml-2 mb-2 block">Valeur</label>
                        <input type="number" step="0.01" name="value" id="off_value" required placeholder="0.00" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-4 text-white font-bold outline-none">
                    </div>
                </div>
                <div>
                    <label class="text-[9px] font-black text-gray-500 uppercase ml-2 mb-2 block tracking-widest text-blue-400">Achat Minimum (DT)</label>
                    <input type="number" step="0.1" name="min_order_amount" id="off_min" value="0.00" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white font-bold outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 p-6 bg-white/[0.02] rounded-3xl border border-white/5">
                <div>
                    <label class="text-[9px] font-black text-gray-500 uppercase mb-2 block italic tracking-widest">Date de Début</label>
                    <input type="datetime-local" name="start_date" id="off_start" required class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-4 text-white font-bold outline-none focus:border-[#007cf0]">
                </div>
                <div>
                    <label class="text-[9px] font-black text-gray-500 uppercase mb-2 block italic tracking-widest">Date d'Expiration</label>
                    <input type="datetime-local" name="end_date" id="off_end" required class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-4 text-white font-bold outline-none focus:border-red-500/50">
                </div>
            </div>

            <div class="flex items-center mb-8 ml-2">
                <label class="flex items-center cursor-pointer group">
                    <input type="checkbox" name="is_active" id="off_active" value="1" class="hidden peer">
                    <div class="w-12 h-6 bg-gray-700 rounded-full peer peer-checked:bg-green-500 transition-all after:content-[''] after:absolute after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-6 after:top-[2px] after:left-[2px] relative"></div>
                    <span class="ml-4 text-[10px] text-gray-400 font-black uppercase tracking-[2px] group-hover:text-white transition">Activer l'offre maintenant</span>
                </label>
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="closeModal()" class="flex-1 py-4 text-[10px] uppercase font-black text-gray-500 hover:text-white transition">Annuler</button>
                <button type="submit" class="flex-1 py-4 bg-gradient-to-r from-[#007cf0] to-[#00dfd8] rounded-2xl text-black text-[10px] font-black uppercase hover:scale-[1.02] transition shadow-xl shadow-blue-500/20">Enregistrer l'Offre</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('offerModal');
    const form = document.getElementById('offerForm');
    const title = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    function openAddModal() {
        title.innerHTML = 'Créer une <span class="text-blue-gradient">Offre</span>';
        form.action = "{{ route('admin.offers.store') }}";
        methodField.innerHTML = ''; 
        form.reset();
        
        // Initialiser avec la date actuelle pour simplifier
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('off_start').value = now.toISOString().slice(0, 16);
        
        document.getElementById('off_active').checked = true;
        modal.classList.remove('hidden');
    }

    function openEditModal(offer) {
        title.innerHTML = 'Modifier <span class="text-blue-gradient">l\'Offre</span>';
        form.action = `/admin/offres/${offer.id}`; 
        methodField.innerHTML = '@method("PUT")';
        
        document.getElementById('off_name').value = offer.name;
        document.getElementById('off_code').value = offer.code || '';
        document.getElementById('off_type').value = offer.type;
        document.getElementById('off_value').value = offer.value;
        document.getElementById('off_min').value = offer.min_order_amount;
        
        // Correction format date pour datetime-local (YYYY-MM-DDTHH:mm)
        if(offer.start_date) {
            document.getElementById('off_start').value = offer.start_date.replace(' ', 'T').slice(0, 16);
        }
        if(offer.end_date) {
            document.getElementById('off_end').value = offer.end_date.replace(' ', 'T').slice(0, 16);
        }

        document.getElementById('off_active').checked = offer.is_active == 1;

        // Gestion du plat ciblé
        document.getElementById('off_dish').value = offer.dish_id || '';

        modal.classList.remove('hidden');
    }

    function closeModal() { modal.classList.add('hidden'); }
</script>

<style>
    .text-blue-gradient {
        background: linear-gradient(to right, #007cf0, #00dfd8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(1); /* Rend l'icône calendrier blanche pour le thème sombre */
        cursor: pointer;
    }
</style>
@endsection