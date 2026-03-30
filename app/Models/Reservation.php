<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'guest_count',
        'reservation_date',
        'table_number',
        'status',
        'special_requests'
    ];

    // La réservation appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}