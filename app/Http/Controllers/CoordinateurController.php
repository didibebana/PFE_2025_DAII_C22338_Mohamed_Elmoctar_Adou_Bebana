<?php

namespace App\Http\Controllers;

use App\Models\Axe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CoordinateurController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $axes = Axe::where('coordinateur_id', $user->id)
            ->with(['sousAxes.actions.budget', 'sousAxes.actions.tauxExecutions'])
            ->get();

        $axesCount = $axes->count();
        $sousAxesCount = $axes->pluck('sousAxes')->flatten()->count();
        $actions = $axes->pluck('sousAxes')->flatten()->pluck('actions')->flatten();

        $actionsCount = $actions->count();

        $totalBudget = 0;
        $budgetConsomme = 0;
        $tauxExecutionGlobal = [];
        $actionsRetard = 0;
        $actionsAVenir = [];

        $executionParAxe = $axes->map(function ($axe) use (&$budgetConsomme, &$totalBudget, &$tauxExecutionGlobal, &$actionsRetard, &$actionsAVenir) {
            $tauxAxe = [];
            $totalBudgetAxe = 0;
            $totalConsommeAxe = 0;

            foreach ($axe->sousAxes as $sousAxe) {
                foreach ($sousAxe->actions as $action) {
                    $budget = $action->budget?->montant ?? 0;
                    $taux = $action->tauxExecutions->avg('taux') ?? 0;

                    $totalBudgetAxe += $budget;
                    $consomme = $budget * ($taux / 100);
                    $totalConsommeAxe += $consomme;

                    $tauxAxe[] = $taux;
                    $tauxExecutionGlobal[] = $taux;

                    // Actions en retard ou non démarrées
                    if ($taux === 0 && $action->date_echeance < now()) {
                        $actionsRetard++;
                    }

                    // Actions à venir (dans les 30 prochains jours)
                    if ($action->date_echeance >= now() && $action->date_echeance <= now()->addDays(30)) {
                        $actionsAVenir[] = [
                            'nom' => $action->nom,
                            'date' => $action->date_echeance->format('Y-m-d'),
                        ];
                    }
                }
            }

            $totalBudget += $totalBudgetAxe;
            $budgetConsomme += $totalConsommeAxe;

            $moyenneTaux = count($tauxAxe) ? round(array_sum($tauxAxe) / count($tauxAxe), 2) : 0;

            return [
                'axe' => $axe->nom,
                'moyenne' => $moyenneTaux,
                'budget_total' => $totalBudgetAxe,
                'budget_consomme' => $totalConsommeAxe,
            ];
        });

        $tauxExecutionMoyen = count($tauxExecutionGlobal)
            ? round(array_sum($tauxExecutionGlobal) / count($tauxExecutionGlobal), 2)
            : 0;

        return Inertia::render('Dashboard/Coordinateur', [
            'axesCount' => $axesCount,
            'sousAxesCount' => $sousAxesCount,
            'actionsCount' => $actionsCount,
            'budgetTotal' => $totalBudget,
            'budgetConsomme' => $budgetConsomme,
            'tauxExecutionMoyen' => $tauxExecutionMoyen,
            'executionParAxe' => $executionParAxe,
            'actionsRetard' => $actionsRetard,
            'actionsAVenir' => $actionsAVenir,
        ]);
    }

}
