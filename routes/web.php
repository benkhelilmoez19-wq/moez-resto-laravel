<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Offer;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| MOEZ RESTO - Système de Routes Complet 2026
|--------------------------------------------------------------------------
*/

// --- 1. VITRINE PUBLIQUE ---
Route::get('/', function () {
    $categories = Category::with(['dishes.offer'])->get();
    $offers = Offer::where('is_active', true)
                   ->where(function($query) {
                       $query->whereNull('end_date')
                             ->orWhere('end_date', '>=', now());
                   })->get();

    return view('index', compact('categories', 'offers'));
})->name('index');

/**
 * NOUVELLE ROUTE : RECHERCHE DE PLATS
 * Cette route permet de filtrer les plats par nom ou description
 */
Route::get('/recherche', function (Request $request) {
    $query = $request->input('query');
    
    // On récupère les catégories mais on ne filtre que les plats qui correspondent à la recherche
    $categories = Category::with(['dishes' => function($q) use ($query) {
        $q->where('name', 'LIKE', "%{$query}%")
          ->orWhere('description', 'LIKE', "%{$query}%");
    }, 'dishes.offer'])->get();

    // On ne garde que les catégories qui ont au moins un plat correspondant
    $categories = $categories->filter(function($category) {
        return $category->dishes->count() > 0;
    });

    $offers = Offer::where('is_active', true)->get();

    return view('index', compact('categories', 'offers', 'query'));
})->name('menu.search');


// --- 2. AUTHENTIFICATION (Visiteurs) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// --- 3. ESPACE CLIENT (Connecté) ---
Route::middleware('auth')->group(function () {
    
    Route::get('/home', function () {
        return redirect()->route('index');
    })->name('home');

    // --- GESTION DU PANIER (Cart) ---
    Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
    Route::post('/panier/ajouter/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/panier/modifier/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/panier/supprimer/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // --- PROCESSUS DE COMMANDE (Checkout) ---
    Route::get('/checkout', [CartController::class, 'showCheckout'])->name('cart.checkout_form');
    Route::post('/commande/confirmer', [CartController::class, 'confirmOrder'])->name('cart.confirm');

    // --- SYSTÈME DE RÉSERVATIONS ---
    Route::post('/reservations', function (Request $request) {
        $request->validate([
            'guest_count' => 'required|integer|min:1|max:20',
            'reservation_date' => 'required|date|after:now',
        ]);

        Reservation::create([
            'user_id' => Auth::id(),
            'guest_count' => $request->guest_count,
            'reservation_date' => $request->reservation_date,
            'special_requests' => $request->special_requests,
            'status' => 'pending'
        ]);

        return redirect()->route('index')->with('success', 'Votre demande de réservation a été envoyée avec succès !');
    })->name('reservations.store');

    Route::get('/reservations', function () {
        return redirect()->route('index');
    });
});

// --- 4. ESPACE ADMINISTRATEUR ---
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    // DASHBOARD & ANALYSES
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/revenus', [AdminController::class, 'revenus'])->name('admin.revenus');

    // GESTION DES UTILISATEURS
    Route::get('/utilisateurs', [AdminController::class, 'users'])->name('admin.users');
    Route::put('/utilisateurs/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/utilisateurs/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // GESTION DES PLATS (DISHES)
    Route::get('/plats', [AdminController::class, 'dishes'])->name('admin.dishes');
    Route::post('/plats', [AdminController::class, 'storeDish'])->name('admin.dishes.store');
    Route::put('/plats/{id}', [AdminController::class, 'updateDish'])->name('admin.dishes.update');
    Route::delete('/plats/{id}', [AdminController::class, 'deleteDish'])->name('admin.dishes.delete');

    // GESTION DES CATÉGORIES
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');

    // GESTION DES OFFRES
    Route::get('/offres', [AdminController::class, 'offers'])->name('admin.offers');
    Route::post('/offres', [AdminController::class, 'storeOffer'])->name('admin.offers.store');
    Route::put('/offres/{id}', [AdminController::class, 'updateOffer'])->name('admin.offers.update');
    Route::delete('/offres/{id}', [AdminController::class, 'deleteOffer'])->name('admin.offers.delete');

    // --- GESTION DES COMMANDES (Table orders) ---
    Route::get('/commandes', [AdminController::class, 'orders'])->name('admin.orders');
    Route::post('/commandes/{id}/update', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.update');

    // --- GESTION DES RÉSERVATIONS (Table reservations) ---
    Route::post('/reservations/{id}/update', [AdminController::class, 'updateReservationStatus'])->name('admin.reservation.update');
});