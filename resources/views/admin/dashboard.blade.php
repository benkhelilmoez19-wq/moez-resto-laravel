@extends('layouts.admin')

@section('content')
<div class="relative min-h-screen p-2 md:p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        
        <div class="stat-card group relative overflow-hidden bg-white/[0.03] border border-white/10 p-6 rounded-[2rem] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(0,124,240,0.1)]">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#007cf0]/10 rounded-full blur-2xl group-hover:bg-[#007cf0]/20 transition-all"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="p-3 rounded-2xl bg-[#007cf0]/10 text-[#007cf0] border border-[#007cf0]/20">
                    <i class="fa-solid fa-wallet text-xl"></i>
                </div>
                <span class="text-[9px] font-black text-green-400 bg-green-400/10 px-3 py-1 rounded-full border border-green-400/20 tracking-widest animate-pulse">LIVE</span>
            </div>
            <p class="text-[10px] uppercase tracking-[3px] text-gray-500 mb-1 font-bold">Revenus Estimes</p>
            <h4 class="text-3xl font-black text-white group-hover:text-[#007cf0] transition-colors tracking-tighter">
                {{ number_format($totalRevenue, 0, ',', ' ') }} <span class="text-sm font-light text-gray-500">DT</span>
            </h4>
        </div>

        <div class="stat-card group relative overflow-hidden bg-white/[0.03] border border-white/10 p-6 rounded-[2rem] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(0,223,216,0.1)]">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#00dfd8]/10 rounded-full blur-2xl group-hover:bg-[#00dfd8]/20 transition-all"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="p-3 rounded-2xl bg-[#00dfd8]/10 text-[#00dfd8] border border-[#00dfd8]/20">
                    <i class="fa-solid fa-calendar-check text-xl"></i>
                </div>
                <span class="text-[10px] font-black text-blue-400 bg-blue-500/10 px-3 py-1 rounded-full border border-blue-500/20 tracking-widest">GLOBAL</span>
            </div>
            <p class="text-[10px] uppercase tracking-[3px] text-gray-500 mb-1 font-bold">Réservations</p>
            <h4 class="text-3xl font-black text-white group-hover:text-[#00dfd8] transition-colors tracking-tighter">
                {{ $totalReservations }}
            </h4>
        </div>

        <div class="stat-card group relative overflow-hidden bg-white/[0.03] border border-white/10 p-6 rounded-[2rem] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(168,85,247,0.1)]">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all"></div>
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 rounded-2xl bg-purple-500/10 text-purple-400 border border-purple-500/20">
                    <i class="fa-solid fa-user-plus text-xl"></i>
                </div>
            </div>
            <p class="text-[10px] uppercase tracking-[3px] text-gray-500 mb-1 font-bold">Nouveaux Clients</p>
            <h4 class="text-3xl font-black text-white tracking-tighter">+{{ $newUsersCount }}</h4>
        </div>

        <div class="stat-card group relative overflow-hidden bg-white/[0.03] border border-white/10 p-6 rounded-[2rem] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(251,146,60,0.1)]">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl group-hover:bg-orange-500/20 transition-all"></div>
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 rounded-2xl bg-orange-500/10 text-orange-400 border border-orange-500/20">
                    <i class="fa-solid fa-star text-xl"></i>
                </div>
            </div>
            <p class="text-[10px] uppercase tracking-[3px] text-gray-500 mb-1 font-bold">Satisfaction</p>
            <h4 class="text-3xl font-black text-white tracking-tighter">{{ $averageRating }} <span class="text-sm font-light text-gray-500">/ 5</span></h4>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <div class="lg:col-span-2 bg-white/[0.02] border border-white/10 p-8 rounded-[2.5rem] backdrop-blur-xl">
            <div class="flex justify-between items-center mb-10">
                <h5 class="text-xs uppercase tracking-[4px] font-black text-white italic">Activité Flux (7 Jours)</h5>
                <div class="flex space-x-2">
                    <div class="w-2 h-2 rounded-full bg-[#007cf0]"></div>
                    <div class="w-2 h-2 rounded-full bg-[#00dfd8]"></div>
                </div>
            </div>
            
            <div class="h-64 flex items-end justify-between space-x-4 px-2">
                @foreach($reservationCounts as $index => $count)
                @php 
                    $max = $reservationCounts->max() > 0 ? $reservationCounts->max() : 1;
                    $height = ($count / $max) * 100;
                @endphp
                <div class="flex-1 flex flex-col items-center group">
                    <div class="w-full bg-gradient-to-t from-[#007cf0]/20 via-[#00dfd8]/60 to-[#00dfd8] rounded-2xl transition-all duration-700 hover:scale-110 relative" 
                         style="height: {{ max($height, 8) }}%">
                        <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-white text-black text-[9px] font-black px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-all uppercase whitespace-nowrap">
                            {{ $count }} Rés.
                        </div>
                    </div>
                    <span class="mt-6 text-[10px] uppercase tracking-tighter text-gray-600 font-black group-hover:text-white transition-colors">{{ $days[$index] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <a href="{{ route('admin.dishes') }}" class="flex-1 group relative overflow-hidden p-8 bg-white/5 rounded-[2.5rem] border border-white/5 hover:border-[#007cf0]/30 transition-all duration-500">
                <div class="absolute right-[-10%] bottom-[-10%] text-white/5 group-hover:text-[#007cf0]/10 transition-colors transform group-hover:scale-110">
                    <i class="fa-solid fa-utensils text-8xl"></i>
                </div>
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="w-12 h-12 bg-[#007cf0]/10 rounded-2xl flex items-center justify-center text-[#007cf0] border border-[#007cf0]/20">
                        <i class="fa-solid fa-plus-circle text-xl"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[3px] text-gray-500">Catalogue</span>
                        <h6 class="text-xl font-black text-white uppercase italic tracking-tighter">Nouveau Plat</h6>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.offers') }}" class="flex-1 group relative overflow-hidden p-8 bg-white/5 rounded-[2.5rem] border border-white/5 hover:border-[#00dfd8]/30 transition-all duration-500">
                <div class="absolute right-[-10%] bottom-[-10%] text-white/5 group-hover:text-[#00dfd8]/10 transition-colors transform group-hover:scale-110">
                    <i class="fa-solid fa-fire text-8xl"></i>
                </div>
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div class="w-12 h-12 bg-[#00dfd8]/10 rounded-2xl flex items-center justify-center text-[#00dfd8] border border-[#00dfd8]/20">
                        <i class="fa-solid fa-bolt text-xl"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[3px] text-gray-500">Marketing</span>
                        <h6 class="text-xl font-black text-white uppercase italic tracking-tighter">Lancer Promo</h6>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="bg-white/[0.01] border border-white/10 rounded-[2.5rem] p-8 shadow-2xl backdrop-blur-sm">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h5 class="text-xs uppercase tracking-[4px] font-black text-white">Transactions en Direct</h5>
                <p class="text-[9px] font-bold text-gray-600 uppercase tracking-widest mt-1">Status: Operational</p>
            </div>
            <a href="{{ route('admin.orders') }}" class="px-6 py-2 bg-white/5 rounded-full border border-white/10 text-[9px] font-black uppercase tracking-widest text-gray-400 hover:text-white hover:bg-white/10 transition-all">
                Voir les commandes →
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-[10px] uppercase tracking-[4px] text-gray-600 border-b border-white/5">
                        <th class="pb-6 font-black text-left">Identité Client</th>
                        <th class="pb-6 font-black text-left">Date & Heure</th>
                        <th class="pb-6 font-black text-left">Volume</th>
                        <th class="pb-6 font-black text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($latestReservations as $res)
                    <tr class="group border-b border-white/[0.03] hover:bg-white/[0.02] transition-colors duration-300">
                        <td class="py-6 flex items-center">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-[#007cf0] to-[#00dfd8] mr-5 flex items-center justify-center text-[13px] text-black font-black uppercase shadow-lg group-hover:rotate-6 transition-transform">
                                {{ substr($res->user->name, 0, 2) }}
                            </div>
                            <div>
                                <p class="text-white font-bold tracking-tight text-base">{{ $res->user->name }}</p>
                                <p class="text-[10px] text-gray-600 font-bold uppercase tracking-wider">{{ $res->user->email }}</p>
                            </div>
                        </td>
                        <td class="py-6">
                            <span class="text-gray-400 font-mono text-xs italic">
                                {{ \Carbon\Carbon::parse($res->reservation_date)->format('d.m.Y') }} 
                                <span class="text-[#007cf0] font-black ml-1">— {{ \Carbon\Carbon::parse($res->reservation_date)->format('H:i') }}</span>
                            </span>
                        </td>
                        <td class="py-6">
                            <span class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-[11px] font-black text-white italic">
                                {{ $res->guest_count }} Pers.
                            </span>
                        </td>
                        <td class="py-6 text-right">
                            <button class="opacity-0 group-hover:opacity-100 transition-opacity bg-[#007cf0]/10 text-[#007cf0] text-[10px] font-black uppercase tracking-widest px-5 py-2.5 rounded-xl border border-[#007cf0]/20 hover:bg-[#007cf0] hover:text-white">
                                Détails
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <i class="fa-solid fa-database text-4xl mb-4"></i>
                                <span class="text-[10px] uppercase tracking-[10px] font-black italic">No Live Sync</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Optionnel : Ajout d'une animation d'entrée pour les cartes */
    .stat-card {
        animation: slideUp 0.6s ease-out forwards;
        opacity: 0;
    }
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .stat-card:nth-child(2) { animation-delay: 0.1s; }
    .stat-card:nth-child(3) { animation-delay: 0.2s; }
    .stat-card:nth-child(4) { animation-delay: 0.3s; }
</style>
@endsection