<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view("partiels.login");
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Tentative d'authentification
        if (Auth::attempt($credentials)) {
            // Récupérer l'utilisateur authentifié après la tentative réussie
            $user = auth()->user();

            // Vérifier si l'email a été vérifié
            if ($user->email_verified_at !== null) {
                // Régénérer la session pour des raisons de sécurité
                $request->session()->regenerate(true);

                // Redirection selon le rôle de l'utilisateur
                if($user->role === 'directeur') {
                    return redirect()->route('list.tasks')->with('success', "Bienvunue M(me) " . $user->name." ".$user->prenom . ". Vous avez été connecté.");
                }
                return redirect()->route('tasks.index')->with('success', "Bienvunue M(me) " . $user->name." ".$user->prenom . ". Vous avez été connecté.");
            } else {
                // Si l'email n'est pas vérifié
                Auth::logout();
                return back()->withErrors(['email' => 'Votre e-mail doit être vérifié avant de pouvoir vous connecter.']);
            }
        } else {
            // En cas d'échec d'authentification
            return back()->withErrors([
                'email' => 'E-mail ou mot de passe incorrect.'
            ])->withInput();
        }
    }


    public function logout()
    {
        session()->invalidate();
        return redirect()->route('login');
        auth()->logout();
    }
}