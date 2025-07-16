<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Axe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class ResponsableController extends Controller
{
    //
        public function index() {
        $user = Auth::user();

        $actions = Action::where('responsable_id', $user->id)
            ->with(['budget', 'tauxExecutions', 'sousAxe.axe'])
            ->get();

        // 1. Nombre d’actions
        $actionsCount = $actions->count();

        $tauxExecutionParAxe = [];

        foreach ($actions as $action) {
            $axeNom = $action->sousAxe->axe->nom ?? 'Inconnu';
            $taux = $action->tauxExecutions->avg('taux') ?? 0;

            if (!isset($tauxExecutionParAxe[$axeNom])) {
                $tauxExecutionParAxe[$axeNom] = ['totalTaux' => 0, 'count' => 0];
            }

            $tauxExecutionParAxe[$axeNom]['totalTaux'] += $taux;
            $tauxExecutionParAxe[$axeNom]['count']++;
        }

        $executionParAxe = collect($tauxExecutionParAxe)->map(function ($data, $axe) {
            $moyenne = $data['count'] ? round($data['totalTaux'] / $data['count'], 2) : 0;
            return [
                'axe' => $axe,
                'moyenne' => $moyenne,
            ];
        })->values();

        // 2. Taux d'exécution moyen
        $tauxExecutionGlobal = [];
        $totalBudget = 0;
        $budgetConsomme = 0;

        $executionEvolution = [];
        $today = Carbon::today();

        $actionsEnRetard = [];
        $echeancesImminentes = [];

        foreach ($actions as $action) {
            $taux = $action->tauxExecutions->avg('taux') ?? 0;
            $budget = $action->budget->montant ?? 0;

            $tauxExecutionGlobal[] = $taux;
            $totalBudget += $budget;
            $budgetConsomme += $budget * ($taux / 100);

            // 3. Pour le graphe d’évolution
            foreach ($action->tauxExecutions as $tauxEntry) {
                $mois = Carbon::parse($tauxEntry->created_at)->format('Y-m');
                $executionEvolution[$mois][] = $tauxEntry->taux;
            }

            // 4. Actions en difficulté
            $enRetard = Carbon::parse($action->date_fin)->lt($today) && $taux < 100;
            $budgetDepasse = ($budget > 0) && ($budget * ($taux / 100) > $budget);

            if ($enRetard || $budgetDepasse) {
                $actionsEnRetard[] = [
                    'nom' => $action->nom,
                    'taux' => $taux,
                    'budget' => $budget,
                    'fin' => $action->date_fin,
                    'axe' => $action->sousAxe->axe->nom ?? '',
                ];
            }

            // 5. Échéances imminentes
            if (
                Carbon::parse($action->date_fin)->between($today, $today->copy()->addDays(7))
            ) {
                $echeancesImminentes[] = [
                    'nom' => $action->nom,
                    'fin' => $action->date_fin,
                    'axe' => $action->sousAxe->axe->nom ?? '',
                ];
            }
        }

        // Formater l'évolution mensuelle
        $evolutionData = collect($executionEvolution)->map(function ($tauxArray, $mois) {
            return [
                'mois' => $mois,
                'taux' => round(array_sum($tauxArray) / count($tauxArray), 2),
            ];
        })->values();

        $tauxExecutionMoyen = count($tauxExecutionGlobal)
            ? round(array_sum($tauxExecutionGlobal) / count($tauxExecutionGlobal), 2)
            : 0;

        return Inertia::render('Dashboard/Responsable', [
            'actionsCount' => $actionsCount,
            'budgetTotal' => $totalBudget,
            'budgetConsomme' => $budgetConsomme,
            'tauxExecutionMoyen' => $tauxExecutionMoyen,
            'executionParAxe' => $executionParAxe,
            'evolution' => $evolutionData,
            'echeances' => $echeancesImminentes,
            'difficiles' => $actionsEnRetard,
        ]);
    }

}
