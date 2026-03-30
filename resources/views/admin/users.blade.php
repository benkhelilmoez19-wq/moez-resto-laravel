@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-10">
    <div>
        <h2 class="text-3xl font-black italic uppercase tracking-tighter">
            Gestion des <span class="text-blue-gradient">Utilisateurs</span>
        </h2>
        <p class="text-[10px] text-gray-500 uppercase tracking-[3px] mt-1 font-bold">Base de données clients active</p>
    </div>
    
    <div class="px-6 py-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md">
        <span class="text-[#007cf0] font-black">{{ $users->total() }}</span> 
        <span class="text-[10px] uppercase tracking-widest text-gray-400 ml-2 font-bold">Membres inscrits</span>
    </div>
</div>

<div class="stat-card bg-[#0f172a]/50 border border-white/5 p-8 rounded-[32px]">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-separate border-spacing-y-2">
            <thead>
                <tr class="text-[10px] uppercase tracking-[4px] text-gray-600">
                    <th class="px-6 pb-6 font-black">Identité & Rôle</th>
                    <th class="px-6 pb-6 font-black">Contact Email</th>
                    <th class="px-6 pb-6 font-black">Date d'inscription</th>
                    <th class="px-6 pb-6 text-right font-black">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($users as $user)
                <tr class="group bg-white/[0.01] hover:bg-white/[0.03] transition-all duration-300">
                    <td class="px-6 py-4 rounded-l-2xl border-l border-y border-white/5">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#007cf0] to-[#00dfd8] mr-4 flex items-center justify-center text-black font-black text-xs shadow-lg shadow-blue-500/10 group-hover:rotate-6 transition-transform">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-bold tracking-tight text-white uppercase text-[13px]">{{ $user->name }}</p>
                                <p class="text-[9px] font-black {{ $user->role === 'admin' ? 'text-orange-500' : 'text-[#00dfd8]' }} uppercase tracking-widest mt-0.5">
                                    {{ $user->role ?? 'Customer' }}
                                </p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 border-y border-white/5 text-gray-400 font-medium italic">
                        {{ $user->email }}
                    </td>

                    <td class="px-6 py-4 border-y border-white/5 text-xs text-gray-400 font-bold uppercase tracking-tighter">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>

                    <td class="px-6 py-4 rounded-r-2xl border-r border-y border-white/5 text-right">
                        <div class="flex justify-end space-x-2">
                            <button onclick='openEditModal({!! $user->toJson() !!})' class="p-2 bg-blue-500/10 text-[#007cf0] rounded-lg hover:bg-[#007cf0] hover:text-black transition">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>

                            @if($user->id !== Auth::id())
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Confirmer le bannissement ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-8">{{ $users->links() }}</div>
</div>

<div id="editModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/85 backdrop-blur-md" onclick="closeModal()"></div>
    
    <div class="relative w-full max-w-lg bg-[#0f172a] p-10 rounded-[40px] border border-white/10 shadow-2xl">
        <div class="mb-8">
            <h3 class="text-2xl font-black uppercase italic tracking-tighter text-white">
                Editer <span class="text-blue-gradient">Profil</span>
            </h3>
            <p class="text-[9px] text-gray-500 uppercase tracking-[3px] mt-1 font-bold italic">Modification des informations de contact</p>
        </div>
        
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="text-[9px] uppercase tracking-widest text-blue-400 font-black mb-2 block">Nom Complet</label>
                    <input type="text" name="name" id="edit_name" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white focus:border-[#007cf0] outline-none transition text-sm font-bold">
                </div>

                <div>
                    <label class="text-[9px] uppercase tracking-widest text-blue-400 font-black mb-2 block">Numéro de Téléphone</label>
                    <input type="text" name="phone" id="edit_phone" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white focus:border-[#007cf0] outline-none transition text-sm font-bold" placeholder="+216 ...">
                </div>

                <div>
                    <label class="text-[9px] uppercase tracking-widest text-blue-400 font-black mb-2 block">Adresse Résidentielle</label>
                    <textarea name="address" id="edit_address" rows="3" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white focus:border-[#007cf0] outline-none transition text-sm font-bold"></textarea>
                </div>
            </div>

            <div class="flex gap-4 pt-8">
                <button type="button" onclick="closeModal()" class="flex-1 py-4 rounded-2xl border border-white/10 text-[10px] uppercase font-black hover:bg-white/5 transition tracking-widest text-gray-500">Fermer</button>
                <button type="submit" class="flex-1 py-4 rounded-2xl bg-gradient-to-r from-[#007cf0] to-[#00dfd8] text-black text-[10px] font-black uppercase hover:scale-105 transition tracking-widest shadow-lg shadow-blue-500/20">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(user) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        form.action = `/admin/utilisateurs/${user.id}`;
        
        document.getElementById('edit_name').value = user.name || '';
        document.getElementById('edit_phone').value = user.phone || '';
        document.getElementById('edit_address').value = user.address || '';
        
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>

<style>
    .text-blue-gradient {
        background: linear-gradient(to right, #007cf0, #00dfd8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .pagination { display: flex; justify-content: center; gap: 8px; }
    .page-item .page-link { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); color: #64748b; border-radius: 10px; padding: 8px 16px; font-weight: 800; font-size: 10px; }
    .page-item.active .page-link { background: #007cf0; color: black; border-color: #007cf0; }
</style>
@endsection