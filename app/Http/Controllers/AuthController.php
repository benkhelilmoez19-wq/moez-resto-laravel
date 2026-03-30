<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function showLogin()
    {
        // Si l'utilisateur est déjà connecté, on le redirige selon son rôle
        if (Auth::check()) {
            return Auth::user()->role === 'admin' 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('index');
        }

        return view('auth.login');
    }

    /**
     * Gère la tentative de connexion.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // LOGIQUE DE REDIRECTION PAR RÔLE
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                                 ->with('success', 'Bienvenue dans votre espace Admin, ' . $user->name);
            }

            // REDIRECTION CLIENT : Retour à la vitrine (index.blade.php)
            return redirect()->intended(route('index'))
                             ->with('success', 'Heureux de vous revoir ' . $user->name);
        }

        return back()->withErrors([
            'email' => 'Les identifiants ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Affiche le formulaire d'inscription.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Gère la création d'un nouveau compte client.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'customer', // Sécurité : Toujours 'customer' via le formulaire public
        ]);

        Auth::login($user);

        // REDIRECTION CLIENT : Retour à la vitrine après inscription
        return redirect()->route('index')->with('success', 'Votre compte a été créé avec succès !');
    }

    /**
     * Déconnexion de l'utilisateur.
     * Redirige vers la vitrine (index)
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Sécurisation de la session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // REDIRECTION VERS L'INDEX (Vitrine)
        return redirect()->route('index')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}