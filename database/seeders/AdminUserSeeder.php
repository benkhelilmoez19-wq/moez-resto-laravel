<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Moez Ben Khelil',
            'email' => 'benkhelilmoez19@gmail.com',
            'password' => Hash::make('12345678'),
            'phone' => '+216 20 000 000', // Votre numéro personnel
            'address' => 'Tunis, Tunisie',
            'role' => 'admin', // Assurez-vous d'avoir une colonne 'role' dans votre table users
        ]);
    }
}