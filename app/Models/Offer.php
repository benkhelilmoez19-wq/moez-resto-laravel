<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Offer extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     * Correspond aux colonnes de votre table 'offers'.
     */
    protected $fillable = [
        'name', 
        'code', 
        'type', 
        'value', 
        'min_order_amount', 
        'start_date', 
        'end_date', 
        'is_active'
    ];

    /**
     * Les types de données à convertir automatiquement.
     * Transforme les dates SQL en objets Carbon pour faciliter les comparaisons.
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'is_active'  => 'boolean',
        'value'      => 'decimal:2',
        'min_order_amount' => 'decimal:2',
    ];

    /**
     * Relation : Une offre peut être liée à plusieurs plats.
     * D'après votre SQL, la table 'dishes' possède une clé étrangère 'offer_id'.
     */
    public function dishes(): HasMany
    {
        return $this->hasMany(Dish::class);
    }

    /**
     * Vérifie si l'offre est actuellement valide.
     * Une offre est valide si :
     * 1. Elle est marquée comme active (is_active = 1).
     * 2. La date actuelle est comprise entre 'start_date' et 'end_date'.
     * * @return bool
     */
    public function isValid(): bool
    {
        $now = Carbon::now();
        
        // Si start_date est nul dans la base, l'offre commence immédiatement.
        $start = $this->start_date ?? $now->copy()->subDay();
        
        return $this->is_active && $now->between($start, $this->end_date);
    }

    /**
     * Calcule le montant de la réduction pour un prix donné.
     * Supporte les remises en pourcentage (%) et les montants fixes (DT).
     * * @param float $total Le prix d'origine.
     * @return float Le montant à déduire du prix.
     */
    public function calculateDiscount($total): float
    {
        // On retourne 0 si l'offre est expirée ou si le montant minimum n'est pas atteint.
        if (!$this->isValid() || $total < $this->min_order_amount) {
            return 0;
        }

        if ($this->type === 'percentage') {
            // Exemple : Pour 20.00% sur un plat à 15 DT -> (15 * 20) / 100 = 3 DT.
            return ($total * $this->value) / 100;
        }

        // Pour un type 'fixed', on déduit la valeur brute sans dépasser le prix total.
        return min($this->value, $total);
    }

    /**
     * Scope : Permet de récupérer uniquement les offres actives dans vos requêtes.
     * Exemple d'utilisation dans le contrôleur : Offer::active()->get();
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('end_date', '>=', Carbon::now());
    }
}