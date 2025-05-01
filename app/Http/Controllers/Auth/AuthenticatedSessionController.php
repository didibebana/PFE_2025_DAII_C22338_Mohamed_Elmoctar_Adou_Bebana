<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Vérification si l'utilisateur a bien un rôle
        if ($user->role_id == 1) {
            // Redirection vers le dashboard admin
            return redirect()->route('dashboard');
        } elseif ($user->role_id == 2) {
            // Redirection vers le dashboard coordinateur
            return redirect()->route('dashboard.coordinateur');
        } elseif ($user->role_id == 3) {
            // Redirection vers le dashboard responsable
            return redirect()->route('dashboard.responsable');
        }elseif ($user->role_id == 4) {
            // Redirection vers le dashboard consultant
            return redirect()->route('dashboard.consultant');
        }

        return redirect()->intended(route('/', absolute: false));

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
