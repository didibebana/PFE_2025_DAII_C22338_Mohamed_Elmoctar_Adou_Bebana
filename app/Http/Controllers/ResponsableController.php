<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Axe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ResponsableController extends Controller
{
    //
    public function index() {


    $user = Auth::user();

        // Récupère uniquement les actions assignées à ce responsable
        $actions = Action::where('responsable_id', $user->id)
            ->with(['budget', 'tauxExecutions'])
            ->get();

        $actionsCount = $actions->count();
        $totalBudget = 0;
        $budgetConsomme = 0;
        $tauxExecutionGlobal = [];

        foreach ($actions as $action) {
            $budget = $action->budget?->montant ?? 0;
            $taux = $action->tauxExecutions->avg('taux') ?? 0;

            $totalBudget += $budget;
            $consomme = $budget * ($taux / 100);
            $budgetConsomme += $consomme;

            $tauxExecutionGlobal[] = $taux;
        }

        $tauxExecutionMoyen = count($tauxExecutionGlobal)
            ? round(array_sum($tauxExecutionGlobal) / count($tauxExecutionGlobal), 2)
            : 0;

        return Inertia::render('Dashboard/Responsable', [
            'actionsCount' => $actionsCount,
            'budgetTotal' => $totalBudget,
            'budgetConsomme' => $budgetConsomme,
            'tauxExecutionMoyen' => $tauxExecutionMoyen,
        ]);
    }

}
