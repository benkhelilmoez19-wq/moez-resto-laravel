<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil avec les offres et le menu.
     */
    public function index()
    {
        // 1. Récupère les offres actives 
        // On vérifie que is_active est à 1 et que la date de fin n'est pas passée
        $offers = Offer::where('is_active', 1)
            ->where('end_date', '>=', Carbon::today()) 
            ->get();

        // 2. Récupère les catégories avec leurs plats (Eager Loading)
        // Seuls les plats disponibles sont affichés
        $categories = Category::with(['dishes' => function($query) {
            $query->where('is_available', 1);
        }])->get();

        // 3. Retourne la vue avec les données
        return view('index', compact('offers', 'categories'));
    }
}