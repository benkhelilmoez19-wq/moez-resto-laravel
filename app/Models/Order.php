<?php

namespace App\Http\Controllers;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés massivement.
     * Basé sur la structure de la table 'orders' de votre SQL.
     */
    protected $fillable = [
        'user_id', 
        'total_price', 
        'status', 
        'delivery_address'
    ];

    /**
     * Les types de conversion pour les colonnes.
     */
    protected $casts = [
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Constantes pour les statuts (basé sur l'ENUM de votre SQL).
     * Cela évite les erreurs de frappe dans votre code.
     */
    const STATUS_PENDING   = 'pending';
    const STATUS_PREPARING = 'preparing';
    const STATUS_SHIPPED   = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Relation : Une commande appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Une commande a plusieurs articles (lignes de commande).
     * Lié à la table 'order_items'.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relation : Une commande peut être liée à un paiement.
     * Note : Votre SQL possède une table 'payments' liée au user_id.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Helper pour vérifier si la commande est annulable.
     */
    public function isDeletable()
    {
        return $this->status === self::STATUS_PENDING;
    }
}