<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // On vérifie si l'utilisateur est connecté ET s'il est admin
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Sinon, retour à l'accueil avec un message
        return redirect('/')->with('error', 'Accès restreint.');
    }
}