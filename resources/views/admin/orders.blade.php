@extends('layouts.admin')

@section('content')
<div class="p-6 bg-gray-900 min-h-screen">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                <i class="fas fa-concierge-bell text-blue-500"></i>
                Tableau de Bord des Ventes & Réservations
            </h1>
            <p class="text-gray-500 text-sm mt-1">Suivi en temps réel des commandes et des tables réservées.</p>
        </div>
    </div>

    <div class="mb-12">
        <div class="flex items-center gap-4 mb-4">
            <h2 class="text-lg font-black italic uppercase text-cyan-400 tracking-widest">
                <i class="fas fa-box-open mr-2"></i> Commandes en ligne
            </h2>
            <span class="px-3 py-0.5 bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 rounded-full text-[10px] font-bold">
                {{ $orders->count() }} TOTAL
            </span>
        </div>

        <div class="bg-gray-800 rounded-3xl shadow-2xl border border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-700/30 text-gray-400 uppercase text-[10px] font-bold tracking-[2px]">
                            <th class="px-6 py-4">Réf & Date</th>
                            <th class="px-6 py-4">Client / Adresse</th>
                            <th class="px-6 py-4">Articles</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Statut</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($orders as $order)
                        <tr class="hover:bg-white/[0.02] transition-all">
                            <td class="px-6 py-4">
                                <span class="font-mono text-cyan-400 font-bold">#CMD-{{ $order->id }}</span>
                                <p class="text-[10px] text-gray-500">{{ $order->created_at->format('d/m H:i') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-white">{{ $order->user->name ?? 'Client' }}</div>
                                <div class="text-[10px] text-gray-500 truncate max-w-[180px] italic">
                                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $order->delivery_address ?? 'Sur place' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($order->items as $item)
                                        <span class="text-[10px] bg-gray-700 px-2 py-0.5 rounded text-gray-300">
                                            {{ $item->quantity }}x {{ Str::limit($item->dish->name, 15) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-black text-cyan-400">{{ number_format($order->total_price, 3) }} <small>DT</small></span>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" 
                                        class="bg-gray-900 text-[10px] font-bold uppercase rounded-lg px-2 py-1 border border-white/10 focus:ring-cyan-500
                                        {{ $order->status == 'pending' ? 'text-amber-500' : ($order->status == 'delivered' ? 'text-green-500' : 'text-blue-500') }}">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Attente</option>
                                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Cuisine</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Livraison</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livré</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2 mt-2">
                                <button onclick="toggleModal('modal-order-{{ $order->id }}')" class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-400 hover:bg-cyan-500 hover:text-white transition shadow-lg shadow-cyan-500/10">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                <button class="text-gray-600 hover:text-white transition"><i class="fas fa-print"></i></button>
                            </td>
                        </tr>

                        <div id="modal-order-{{ $order->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/80 backdrop-blur-sm">
                            <div class="flex items-center justify-center min-h-screen p-4">
                                <div class="bg-gray-800 border border-gray-700 w-full max-w-lg rounded-3xl p-6 shadow-2xl relative">
                                    <button onclick="toggleModal('modal-order-{{ $order->id }}')" class="absolute top-4 right-4 text-gray-500 hover:text-white"><i class="fas fa-times"></i></button>
                                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                                        <i class="fas fa-receipt text-cyan-400"></i> Détails Commande #{{ $order->id }}
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="bg-gray-900/50 p-4 rounded-2xl border border-white/5">
                                            <p class="text-[10px] text-gray-500 uppercase font-black mb-3 tracking-widest">Contenu du panier</p>
                                            @foreach($order->items as $item)
                                            <div class="flex justify-between items-center py-2 border-b border-white/5 last:border-0">
                                                <span class="text-gray-300 text-sm">{{ $item->quantity }}x {{ $item->dish->name }}</span>
                                                <span class="text-white font-mono text-sm">{{ number_format($item->price * $item->quantity, 3) }} DT</span>
                                            </div>
                                            @endforeach
                                            <div class="mt-4 pt-4 border-t border-cyan-500/30 flex justify-between">
                                                <span class="text-cyan-400 font-bold">TOTAL</span>
                                                <span class="text-cyan-400 font-black">{{ number_format($order->total_price, 3) }} DT</span>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 text-[11px]">
                                            <div class="p-3 bg-gray-900/30 rounded-xl">
                                                <p class="text-gray-500 uppercase">Client</p>
                                                <p class="text-white font-bold">{{ $order->user->name ?? 'N/A' }}</p>
                                            </div>
                                            <div class="p-3 bg-gray-900/30 rounded-xl">
                                                <p class="text-gray-500 uppercase">Adresse</p>
                                                <p class="text-white font-bold italic">{{ $order->delivery_address ?? 'Sur place' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="6" class="py-10 text-center text-gray-600 italic">Aucune commande aujourd'hui.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div>
        <div class="flex items-center gap-4 mb-4">
            <h2 class="text-lg font-black italic uppercase text-blue-500 tracking-widest">
                <i class="fas fa-calendar-alt mr-2"></i> Réservations Tables
            </h2>
            <span class="px-3 py-0.5 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-full text-[10px] font-bold">
                {{ $reservations->count() }} RDV
            </span>
        </div>

        <div class="bg-gray-800 rounded-3xl shadow-2xl border border-gray-700 overflow-hidden text-gray-300">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-700/50 text-gray-400 uppercase text-[10px] font-bold tracking-widest">
                            <th class="px-6 py-4">Réf & Date</th>
                            <th class="px-6 py-4">Client</th>
                            <th class="px-6 py-4">Détails</th>
                            <th class="px-6 py-4">Statut</th>
                            <th class="px-6 py-4 text-right">Actions rapides</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($reservations as $res)
                        <tr class="hover:bg-blue-500/[0.02] transition-all">
                            <td class="px-6 py-4">
                                <span class="block font-mono text-blue-400 text-sm font-bold">#RES-{{ $res->id }}</span>
                                <span class="text-[10px] text-gray-500 uppercase">{{ $res->created_at->format('d M Y à H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-500 font-bold text-xs">
                                        {{ substr($res->user->name ?? 'C', 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-white text-sm">{{ $res->user->name ?? 'Client' }}</span>
                                        <span class="text-[11px] text-gray-500 italic">{{ $res->user->phone ?? 'S/N' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-users text-gray-600 text-xs"></i>
                                    <span class="font-medium">{{ $res->guest_count }} pers.</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $config = [
                                        'pending' => ['l' => 'Attente', 'c' => 'text-amber-500 bg-amber-500/10 border-amber-500/20'],
                                        'confirmed' => ['l' => 'Confirmé', 'c' => 'text-blue-500 bg-blue-500/10 border-blue-500/20'],
                                        'cancelled' => ['l' => 'Annulé', 'c' => 'text-red-500 bg-red-500/10 border-red-500/20'],
                                    ][$res->status] ?? ['l' => $res->status, 'c' => 'text-gray-500 bg-gray-500/10'];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter border {{ $config['c'] }}">
                                    {{ $config['l'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <button onclick="toggleModal('modal-res-{{ $res->id }}')" class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>

                                    @if($res->status == 'pending')
                                    <form action="{{ route('admin.reservation.update', $res->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="confirmed">
                                        <button class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition">
                                            <i class="fas fa-check text-xs"></i>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    @if($res->status != 'cancelled')
                                    <form action="{{ route('admin.reservation.update', $res->id) }}" method="POST" onsubmit="return confirm('Annuler ?')">
                                        @csrf
                                        <input type="hidden" name="status" value="cancelled">
                                        <button class="w-8 h-8 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <div id="modal-res-{{ $res->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/80 backdrop-blur-sm">
                            <div class="flex items-center justify-center min-h-screen p-4">
                                <div class="bg-gray-800 border border-gray-700 w-full max-w-md rounded-3xl p-6 shadow-2xl relative">
                                    <button onclick="toggleModal('modal-res-{{ $res->id }}')" class="absolute top-4 right-4 text-gray-500 hover:text-white"><i class="fas fa-times"></i></button>
                                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                                        <i class="fas fa-calendar-check text-blue-500"></i> Réservation #{{ $res->id }}
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="p-4 bg-gray-900/50 rounded-2xl border border-white/5 space-y-3">
                                            <div class="flex justify-between">
                                                <span class="text-gray-500 text-xs">Nombre de convives :</span>
                                                <span class="text-white font-bold">{{ $res->guest_count }} personnes</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-500 text-xs">Date & Heure :</span>
                                                <span class="text-blue-400 font-bold">{{ $res->created_at->format('d/m/Y à H:i') }}</span>
                                            </div>
                                        </div>
                                        <div class="p-4 bg-gray-900/30 rounded-2xl border border-dashed border-gray-700">
                                            <p class="text-[10px] text-gray-500 uppercase font-black mb-2">Demandes Spéciales</p>
                                            <p class="text-gray-300 text-sm italic">"{{ $res->special_requests ?? 'Aucune note particulière' }}"</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="5" class="py-10 text-center text-gray-600 italic">Aucune réservation.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Empêche le scroll en arrière-plan
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }
</script>

<style>
    .overflow-x-auto::-webkit-scrollbar { height: 4px; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #374151; border-radius: 10px; }
</style>
@endsection