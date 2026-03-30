@extends('layouts.admin')

@section('content')
<div class="p-6 bg-gray-900 min-h-screen">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-white flex items-center gap-3">
            <i class="fas fa-wallet text-green-500"></i>
            Suivi des Revenus
        </h1>
        <div class="bg-gray-800 px-4 py-2 rounded-lg border border-gray-700">
            <span class="text-gray-400">Total Global : </span>
            <span class="text-green-400 font-bold text-lg">{{ number_format($totalRevenus, 2) }} DT</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm italic">Commandes Terminées</p>
                    <h3 class="text-2xl font-bold text-white mt-1">{{ $countCompleted }}</h3>
                </div>
                <div class="bg-blue-500/10 p-3 rounded-lg text-blue-500">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm italic">Moyenne / Commande</p>
                    <h3 class="text-2xl font-bold text-white mt-1">
                        {{ $countCompleted > 0 ? number_format($totalRevenus / $countCompleted, 2) : 0 }} DT
                    </h3>
                </div>
                <div class="bg-purple-500/10 p-3 rounded-lg text-purple-500">
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm italic">En attente (Prévisionnel)</p>
                    <h3 class="text-2xl font-bold text-yellow-500 mt-1">{{ number_format($pendingRevenus, 2) }} DT</h3>
                </div>
                <div class="bg-yellow-500/10 p-3 rounded-lg text-yellow-500">
                    <i class="fas fa-clock fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
        <div class="p-4 border-b border-gray-700 bg-gray-700/30">
            <h2 class="text-lg font-semibold text-white">Transactions Récentes</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-400 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Réf Commande</th>
                        <th class="px-6 py-4 font-bold">Client</th>
                        <th class="px-6 py-4 font-bold">Date de Paiement</th>
                        <th class="px-6 py-4 font-bold">Montant</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-700/20 transition-colors">
                            <td class="px-6 py-4 font-mono text-blue-400">#{{ $order->id }}</td>
                            <td class="px-6 py-4 text-white font-medium">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="text-green-400 font-bold">+ {{ number_format($order->total_price, 2) }} DT</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-receipt text-3xl mb-2"></i>
                                <p>Aucun revenu enregistré pour le moment.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection