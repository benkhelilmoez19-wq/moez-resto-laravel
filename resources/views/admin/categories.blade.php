@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-10">
    <div>
        <h2 class="text-3xl font-black italic uppercase tracking-tighter text-white">
            Gestion des <span class="text-blue-gradient">Catégories</span>
        </h2>
        <p class="text-[10px] text-gray-500 uppercase tracking-[3px] mt-1 font-bold">Organisation du menu</p>
    </div>

    <button onclick="openAddModal()" class="px-6 py-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest hover:bg-[#007cf0] hover:text-black transition shadow-lg shadow-blue-500/10">
        + Nouvelle Catégorie
    </button>
</div>

<div class="stat-card bg-[#0f172a]/50 border border-white/5 p-8 rounded-[32px]">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-separate border-spacing-y-2">
            <thead>
                <tr class="text-[10px] uppercase tracking-[4px] text-gray-600">
                    <th class="px-6 pb-6 font-black">Aperçu</th>
                    <th class="px-6 pb-6 font-black">Nom</th>
                    <th class="px-6 pb-6 font-black">Date</th>
                    <th class="px-6 pb-6 text-right font-black">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($categories as $category)
                <tr class="group bg-white/[0.01] hover:bg-white/[0.03] transition-all duration-300">
                    <td class="px-6 py-4 rounded-l-2xl border-l border-y border-white/5">
                        <div class="w-12 h-12 rounded-xl bg-white/5 overflow-hidden border border-white/10">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[10px] text-gray-600 font-bold">N/A</div>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4 border-y border-white/5">
                        <span class="text-white font-black uppercase tracking-tight">{{ $category->name }}</span>
                    </td>

                    <td class="px-6 py-4 border-y border-white/5 text-xs text-gray-400 font-bold uppercase">
                        {{ $category->created_at ? $category->created_at->format('d/m/Y') : 'Ancien' }}
                    </td>

                    <td class="px-6 py-4 rounded-r-2xl border-r border-y border-white/5 text-right">
                        <div class="flex justify-end space-x-2">
                            <button onclick='openEditModal({!! $category->toJson() !!})' class="p-2 bg-blue-500/10 text-[#007cf0] rounded-lg hover:bg-[#007cf0] hover:text-black transition">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>

                            <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" onsubmit="return confirm('Supprimer cette catégorie ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="categoryModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/85 backdrop-blur-md" onclick="closeModal()"></div>
    
    <div class="relative w-full max-w-lg bg-[#0f172a] p-10 rounded-[40px] border border-white/10 shadow-2xl">
        <h3 id="modalTitle" class="text-2xl font-black uppercase italic text-white mb-8">
            Nouvelle <span class="text-blue-gradient">Catégorie</span>
        </h3>
        
        <form id="categoryForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodField"></div> {{-- Pour le PUT en édition --}}
            
            <div class="space-y-6">
                <div>
                    <label class="text-[9px] uppercase tracking-widest text-blue-400 font-black mb-2 block">Nom de la catégorie</label>
                    <input type="text" name="name" id="cat_name" required class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white focus:border-[#007cf0] outline-none transition font-bold">
                </div>

                <div>
                    <label class="text-[9px] uppercase tracking-widest text-blue-400 font-black mb-2 block">Image (Optionnel)</label>
                    <div class="relative">
                        <input type="file" name="image" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-gray-400 text-xs font-bold">
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-8">
                <button type="button" onclick="closeModal()" class="flex-1 py-4 rounded-2xl border border-white/10 text-[10px] uppercase font-black text-gray-500 hover:bg-white/5 transition">Annuler</button>
                <button type="submit" class="flex-1 py-4 rounded-2xl bg-gradient-to-r from-[#007cf0] to-[#00dfd8] text-black text-[10px] font-black uppercase hover:scale-105 transition shadow-lg shadow-blue-500/20">Valider</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');
    const title = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    function openAddModal() {
        title.innerHTML = 'Nouvelle <span class="text-blue-gradient">Catégorie</span>';
        form.action = "{{ route('admin.categories.store') }}"; // Assure-toi que cette route existe
        methodField.innerHTML = ''; 
        document.getElementById('cat_name').value = '';
        modal.classList.remove('hidden');
    }

    function openEditModal(category) {
        title.innerHTML = 'Modifier <span class="text-blue-gradient">Catégorie</span>';
        form.action = `/admin/categories/${category.id}`; 
        methodField.innerHTML = '@method("PUT")';
        document.getElementById('cat_name').value = category.name;
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
</script>

<style>
    .text-blue-gradient {
        background: linear-gradient(to right, #007cf0, #00dfd8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endsection