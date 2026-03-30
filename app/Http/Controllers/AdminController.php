<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reservation;
use App\Models\Order; // Ajout du modèle Order
use App\Models\Dish;
use App\Models\Category;
use App\Models\Offer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // --- 1. DASHBOARD & STATISTIQUES ---
    public function index() {
        // Stats de base (Réservations)
        $totalReservations = Reservation::count();
        
        // Revenus : On additionne le total de la table 'orders' + ton calcul des réservations
        $revenueFromOrders = Order::whereIn('status', ['delivered', 'shipped'])->sum('total_price');
        $revenueFromReservations = Reservation::where('status', 'confirmed')->count() * 85; 
        $totalRevenue = $revenueFromOrders + $revenueFromReservations;
        
        $newUsersCount = User::where('created_at', '>=', now()->subMonth())->count();
        $averageRating = 4.9;

        $latestReservations = Reservation::with('user')->latest()->take(5)->get();
        // Ajout des dernières commandes pour le dashboard
        $latestOrders = Order::with('user')->latest()->take(5)->get();

        // Données pour le graphique (7 derniers jours)
        $days = collect();
        $orderCounts = collect();
        $reservationCounts = collect(); // Initialisation de la variable manquante

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $days->push($date->format('D'));
            
            // Statistiques des commandes par jour
            $orderCounts->push(Order::whereDate('created_at', $date)->count());
            
            // Statistiques des réservations par jour (pour corriger l'erreur de la vue)
            $reservationCounts->push(Reservation::whereDate('created_at', $date)->count());
        }

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'totalReservations', 
            'newUsersCount', 
            'averageRating', 
            'latestReservations', 
            'latestOrders', 
            'days', 
            'orderCounts',
            'reservationCounts' // Envoi de la variable à la vue dashboard
        ));
    }

    // --- 2. GESTION DES REVENUS ---
    public function revenus() {
        // Revenus basés sur les commandes réelles et les réservations
        $totalRevenus = Order::whereIn('status', ['delivered', 'shipped'])->sum('total_price') + (Reservation::where('status', 'confirmed')->count() * 85);
        $pendingRevenus = Order::where('status', 'pending')->sum('total_price');
        $countCompleted = Order::where('status', 'delivered')->count();

        $recentOrders = Order::with('user')
                            ->latest()
                            ->take(10)
                            ->get();

        return view('admin.revenus', compact('totalRevenus', 'pendingRevenus', 'countCompleted', 'recentOrders'));
    }

    // --- 3. TRAITEMENT DES RÉSERVATIONS ET COMMANDES ---
    public function orders() {
        // On récupère les réservations (ton code original)
        $reservations = Reservation::with('user')->latest()->get();
        
        // On récupère les commandes de plats (table orders)
        $orders = Order::with(['user', 'items.dish'])->latest()->get();
        
        return view('admin.orders', compact('reservations', 'orders'));
    }

    /**
     * Mise à jour du statut d'une COMMANDE (Table orders)
     */
    public function updateOrderStatus(Request $request, $id) {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,preparing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);
        return redirect()->back()->with('success', "Le statut de la commande #{$id} a été mis à jour.");
    }

    /**
     * Mise à jour du statut d'une RÉSERVATION (Table reservations)
     */
    public function updateReservationStatus(Request $request, $id) {
        $reservation = Reservation::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        $reservation->update(['status' => $request->status]);

        $msg = "Statut de la réservation mis à jour.";
        if($request->status == 'confirmed') $msg = "La réservation a été acceptée.";
        if($request->status == 'cancelled') $msg = "La réservation a été annulée.";

        return redirect()->back()->with('success', $msg);
    }

    // --- 4. GESTION DES UTILISATEURS ---
    public function users() {
        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function updateUser(Request $request, $id) {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:customer,admin'
        ]);

        $user->update($request->only(['name', 'phone', 'address', 'role']));
        return redirect()->back()->with('success', "Profil de {$user->name} mis à jour.");
    }

    public function deleteUser($id) {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Action impossible : vous êtes connecté sur ce compte.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'Utilisateur supprimé.');
    }

    // --- 5. GESTION DES CATÉGORIES ---
    public function categories() {
        $categories = Category::latest()->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        Category::create([
            'name' => $request->name,
            'image' => $imagePath
        ]);

        return redirect()->back()->with('success', 'Catégorie créée.');
    }

    public function updateCategory(Request $request, $id) {
        $category = Category::findOrFail($id);
        $request->validate(['name' => 'required|string|max:255']);

        $data = ['name' => $request->name];
        if ($request->hasFile('image')) {
            if ($category->image) Storage::disk('public')->delete($category->image);
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);
        return redirect()->back()->with('success', 'Catégorie mise à jour.');
    }

    public function deleteCategory($id) {
        $category = Category::findOrFail($id);
        if ($category->image) Storage::disk('public')->delete($category->image);
        $category->delete();
        return redirect()->back()->with('success', 'Catégorie supprimée.');
    }

    // --- 6. GESTION DES PLATS (DISHES) ---
    public function dishes() {
        $dishes = Dish::with('category', 'offer')->latest()->get();
        $categories = Category::all();
        return view('admin.dishes', compact('dishes', 'categories'));
    }

    public function storeDish(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string', // Ajouté pour corriger l'affichage
            'image' => 'required|image'
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('dishes', 'public');
        }

        Dish::create($data);
        return redirect()->back()->with('success', 'Plat ajouté au menu.');
    }

    public function updateDish(Request $request, $id) {
        $dish = Dish::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string', // Ajouté pour permettre la modif
            'image' => 'nullable|image'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            if ($dish->image) Storage::disk('public')->delete($dish->image);
            $data['image'] = $request->file('image')->store('dishes', 'public');
        }

        $dish->update($data);
        return redirect()->back()->with('success', 'Plat mis à jour.');
    }

    public function deleteDish($id) {
        $dish = Dish::findOrFail($id);
        if ($dish->image) Storage::disk('public')->delete($dish->image);
        $dish->delete();
        return redirect()->back()->with('success', 'Plat supprimé.');
    }

    // --- 7. GESTION DES OFFRES PROMO ---
    public function offers() {
        $offers = Offer::latest()->get();
        $dishes = Dish::all(); 
        return view('admin.offers', compact('offers', 'dishes'));
    }

    public function storeOffer(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:offers,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric',
            'end_date' => 'required|date',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        if (!$request->filled('start_date')) $data['start_date'] = now();

        $offer = Offer::create($data);

        if ($request->filled('dish_id')) {
            Dish::where('id', $request->dish_id)->update(['offer_id' => $offer->id]);
        }

        return redirect()->back()->with('success', 'Offre promotionnelle activée.');
    }

    public function deleteOffer($id) {
        $offer = Offer::findOrFail($id);
        Dish::where('offer_id', $offer->id)->update(['offer_id' => null]);
        $offer->delete();
        return redirect()->back()->with('success', 'Offre supprimée.');
    }
}