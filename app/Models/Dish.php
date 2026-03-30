<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    // Ajout de 'description' et 'is_available' dans le tableau fillable
    protected $fillable = [
        'name', 
        'description', // <--- CRITIQUE : Permet l'enregistrement du texte
        'price', 
        'image', 
        'category_id', 
        'offer_id',
        'is_available' // Présent dans ta structure SQL
    ];

    // 1. La relation avec la catégorie
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 2. La relation avec l'offre
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}