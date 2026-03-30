@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-10">
    <div>
        <h2 class="text-3xl font-black italic uppercase tracking-tighter text-white">
            Menu <span class="text-blue-gradient">Élite</span>
        </h2>
        <p class="text-[10px] text-gray-500 uppercase tracking-[3px] mt-1 font-bold italic underline decoration-blue-500/50">Gestion visuelle des plats</p>
    </div>

    <button onclick="openAddModal()" class="px-6 py-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest hover:bg-[#007cf0] hover:text-black transition shadow-lg shadow-blue-500/10 active:scale-95">
        + Ajouter un Plat
    </button>
</div>

@if(session('success'))
    <div class="mb-8 p-4 bg-blue-500/10 border border-blue-500/20 text-[#00dfd8] rounded-2xl text-[10px] uppercase font-black tracking-[2px] animate-pulse">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($dishes as $dish)
    <div class="group relative bg-[#0f172a]/40 border border-white/5 rounded-[32px] overflow-hidden hover:border-blue-500/30 transition-all duration-500 hover:-translate-y-2">
        <div class="relative h-48 w-full overflow-hidden">
            @if($dish->image)
                <img src="{{ asset('storage/' . $dish->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            @else
                <div class="w-full h-full bg-white/5 flex items-center justify-center">
                    <i class="fa-solid fa-utensils text-gray-700 text-3xl"></i>
                </div>
            @endif
            
            <div class="absolute top-4 left-4">
                <span class="px-3 py-1.5 bg-black/60 backdrop-blur-md border border-white/10 text-[#00dfd8] text-[8px] font-black uppercase rounded-lg">
                    {{ $dish->category->name ?? 'Général' }}
                </span>
            </div>

            <div class="absolute inset-0 bg-black/60 flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <button onclick='openEditModal({!! $dish->toJson() !!})' class="w-10 h-10 bg-white/10 hover:bg-[#007cf0] text-white hover:text-black rounded-xl backdrop-blur-md transition flex items-center justify-center">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <form action="{{ route('admin.dishes.delete', $dish->id) }}" method="POST" onsubmit="return confirm('Supprimer ce plat ?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-10 h-10 bg-red-500/20 hover:bg-red-500 text-red-500 hover:text-white rounded-xl backdrop-blur-md transition flex items-center justify-center">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="p-6">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-white font-black uppercase tracking-tight text-lg">{{ $dish->name }}</h3>
                <span class="text-[#007cf0] font-black italic text-sm">{{ number_format($dish->price, 2) }} DT</span>
            </div>
            <p class="text-gray-500 text-[11px] leading-relaxed line-clamp-2 h-8">
                {{ $dish->description ?? 'Aucune description disponible pour ce plat.' }}
            </p>
        </div>
        
        <div class="h-1 w-full bg-gradient-to-r from-transparent via-blue-500/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
    </div>
    @endforeach
</div>

@if($dishes->isEmpty())
    <div class="text-center py-20 bg-white/[0.02] rounded-[40px] border border-dashed border-white/10">
        <p class="text-gray-600 text-xs uppercase font-black tracking-[4px]">Votre menu est vide</p>
    </div>
@endif

<div id="dishModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/90 backdrop-blur-xl" onclick="closeModal()"></div>
    
    <div class="relative w-full max-w-2xl bg-[#0f172a] p-10 rounded-[40px] border border-white/10 shadow-2xl">
        <h3 id="modalTitle" class="text-2xl font-black uppercase italic text-white mb-8">Nouveau <span class="text-blue-gradient">Plat</span></h3>
        
        <form id="dishForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodField"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-5">
                    <input type="text" name="name" id="dish_name" placeholder="NOM DU PLAT" required class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white outline-none focus:border-[#007cf0] font-bold">
                    <select name="category_id" id="dish_category" required class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white outline-none focus:border-[#007cf0] font-bold">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" class="bg-[#0f172a]">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" step="0.01" name="price" id="dish_price" placeholder="PRIX (DT)" required class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white outline-none focus:border-[#007cf0] font-bold">
                </div>
                <div class="space-y-5">
                    <textarea name="description" id="dish_desc" placeholder="DESCRIPTION..." rows="4" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white outline-none focus:border-[#007cf0] font-bold"></textarea>
                    <input type="file" name="image" class="w-full text-xs text-gray-500 font-bold file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-500/10 file:text-blue-500 hover:file:bg-blue-500/20">
                </div>
            </div>
            <div class="flex gap-4 pt-10">
                <button type="button" onclick="closeModal()" class="flex-1 py-4 text-[10px] uppercase font-black text-gray-500">Fermer</button>
                <button type="submit" class="flex-1 py-4 bg-gradient-to-r from-[#007cf0] to-[#00dfd8] rounded-2xl text-black text-[10px] font-black uppercase shadow-lg shadow-blue-500/20">Sauvegarder</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('dishModal');
    const form = document.getElementById('dishForm');
    const title = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    function openAddModal() {
        title.innerHTML = 'Nouveau <span class="text-blue-gradient">Plat</span>';
        form.action = "{{ route('admin.dishes.store') }}";
        methodField.innerHTML = ''; 
        form.reset();
        modal.classList.remove('hidden');
    }

    function openEditModal(dish) {
        title.innerHTML = 'Modifier <span class="text-blue-gradient">Plat</span>';
        form.action = `/admin/plats/${dish.id}`; 
        methodField.innerHTML = '@method("PUT")';
        document.getElementById('dish_name').value = dish.name;
        document.getElementById('dish_category').value = dish.category_id;
        document.getElementById('dish_price').value = dish.price;
        document.getElementById('dish_desc').value = dish.description;
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
</style>
@endsection