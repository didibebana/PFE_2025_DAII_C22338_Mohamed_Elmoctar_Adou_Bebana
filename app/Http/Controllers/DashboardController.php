<?php

namespace App\Http\Controllers;

use App\Models\Axe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{


    public function index()
    {
        $user = Auth::user();

        if ($user->role->nom === 'administrateur') {
            return redirect()->route('dashboard.admin');
        } elseif ($user->role->nom === 'coordinateur_axe') {
            return redirect()->route('dashboard.coordinateur');
        } elseif ($user->role->nom === 'responsable_action') {
            return redirect()->route('dashboard.responsable');
        } elseif ($user->role->nom === 'consultant') {
            return redirect()->route('dashboard.consultant');
        }

        // Rediriger vers une page par défaut (par exemple, une page d'erreur)
        return redirect()->route('login');
    }


}
