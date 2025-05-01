<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Axe;
use App\Models\Budget;
use App\Models\SousAxe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
         $user = Auth::user();
         // Compter les éléments
         $axesCount = Axe::count();
         $sousAxesCount = SousAxe::count();
         $actionsCount = Action::count();
         $usersCount = User::count();

         // Récupérer tous les budgets avec leurs actions
         $budgets = Budget::with('action')->get();
         $totalBudget = $budgets->sum('montant');

         // Calculer le budget consommé en fonction des taux d'exécution
         $budgetConsomme = 0;
         $tauxExecutionGlobal = [];

         $executionParAxe = Axe::with(['sousAxes.actions.budget', 'sousAxes.actions.tauxExecutions'])
             ->get()
             ->map(function ($axe) use (&$budgetConsomme, &$tauxExecutionGlobal) {
                 $tauxAxe = [];
                 $totalBudgetAxe = 0;
                 $totalConsommeAxe = 0;

                 foreach ($axe->sousAxes as $sousAxe) {
                     foreach ($sousAxe->actions as $action) {
                         // Budget total de l'action
                         $budgetAction = $action->budget?->montant ?? 0;
                         $totalBudgetAxe += $budgetAction;

                         // Taux d'exécution moyen de l'action
                         $tauxAction = $action->tauxExecutions->avg('taux') ?? 0;
                         $tauxAxe[] = $tauxAction;
                         $tauxExecutionGlobal[] = $tauxAction;

                         // Budget consommé pour cette action
                         $consommeAction = $budgetAction * ($tauxAction / 100);
                         $totalConsommeAxe += $consommeAction;
                     }
                 }

                 $budgetConsomme += $totalConsommeAxe;

                 $moyenneTaux = count($tauxAxe) > 0 ? round(array_sum($tauxAxe) / count($tauxAxe), 2) : 0;

                 return [
                     'axe' => $axe->nom,
                     'moyenne' => $moyenneTaux,
                     'budget_total' => $totalBudgetAxe,
                     'budget_consomme' => $totalConsommeAxe,
                 ];
             });

         // Calcul du taux d'exécution moyen global
         $tauxExecutionMoyen = count($tauxExecutionGlobal) > 0
             ? round(array_sum($tauxExecutionGlobal) / count($tauxExecutionGlobal), 2)
             : 0;

         return Inertia::render('Dashboard/Admin', [
             'axesCount' => $axesCount,
             'sousAxesCount' => $sousAxesCount,
             'actionsCount' => $actionsCount,
             'usersCount' => $usersCount,
             'budgetTotal' => $totalBudget,
             'budgetConsomme' => $budgetConsomme,
             'tauxExecutionMoyen' => $tauxExecutionMoyen,
             'executionParAxe' => $executionParAxe,
         ]);
    }
}
