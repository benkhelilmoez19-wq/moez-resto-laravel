<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'image'];

    // Une catégorie a plusieurs plats
    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }
}