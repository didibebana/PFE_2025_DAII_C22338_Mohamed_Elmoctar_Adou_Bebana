<?php

namespace App\Http\Controllers;

use App\Models\Axe;
use App\Http\Requests\StoreAxeRequest;
use App\Http\Requests\UpdateAxeRequest;
use App\Http\Resources\AxeResource;
use App\Http\Resources\SousAxeResource;
use App\Models\SousAxe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class AxeController extends Controller
{



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $query = Axe::query();

        if ($user->role_id !== 1) {
            abort(403, 'Accès interdit - Administrateur uniquement');
        }



        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'asc');

        if (request('nom')) {
            $query->where('nom', 'like', '%' . request('nom') . '%');
        }

        $axes = $query->orderBy($sortField, $sortDirection)->paginate(10)->onEachSide(1);

        return inertia('Axe/Index', [
            'axes' => AxeResource::collection($axes),
            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Récupérer tous les utilisateurs ayant le rôle 'coordinateur_axe'
        $coordinateurs = User::whereHas('role', function($query) {
            $query->where('nom', 'coordinateur_axe');
        })->get();

        // Retourner la vue avec la liste des coordinateurs
        return inertia('Axe/Create', [
            'coordinateurs' => $coordinateurs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAxeRequest $request)
    {
        $data = $request->validated();
        $coordinateur = User::find($data['coordinateur_id']);

        if (!$coordinateur || !$coordinateur->role || $coordinateur->role->nom !== 'coordinateur_axe') {
            return redirect()->back()->withErrors(['coordinateur' => 'Le coordinateur sélectionné n\'est pas valide ou n\'a pas le rôle approprié.']);
        }

        Axe::create($data);

        $user = Auth::user();
        if ($user->role_id === 2) { // Coordinateur
            return redirect()->route('axe.mesAxes', ['coordinateur_id' => $user->id])->with('success', 'Axe ajouté avec succès.');
        }

        return redirect()->route('axe.index')->with('success', 'Axe ajoutée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,Axe $axe)
    {
        $query = $axe->sousAxes();
        $axe->load('coordinateur');

        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'asc');

        if (request('nom')) {
            $query->where('nom', 'like', '%' . request('nom') . '%');
        }

        $sousaxes = $query->with('axe')->orderBy($sortField, $sortDirection)->paginate(10)->onEachSide(1);


        $totalBudget = 0;
        $budgetConsomme = 0;
        $tauxTotal = [];
        $actionsCount = 0;

        foreach ($axe->sousAxes as $sousAxe) {
            foreach ($sousAxe->actions as $action) {
                // Budget de l'action
                $derniereBudget = $action->dernierBudget ;
                if($derniereBudget){
                    $budget = $derniereBudget->montant;
                    $totalBudget += $budget;

                    // Taux d'exécution
                    $taux = $action->tauxExecutions->avg('taux') ?? 0;
                    $tauxTotal[] = $taux;
                    $actionsCount++;

                    // Budget consommé
                    $budgetConsomme += $budget * ($taux / 100);
                }


            }
        }

        $budgetRestant = $totalBudget - $budgetConsomme;
        $tauxExecutionMoyen = $actionsCount > 0 ? round(array_sum($tauxTotal) / $actionsCount, 2) : 0;

        return inertia('Axe/Show',[
                'axe' => new AxeResource($axe),
                'sousaxes' => SousAxeResource::collection($sousaxes),
                'queryParams' => request()->query() ?: null,
                'stats' => [
                    'totalBudget' => $totalBudget,
                    'budgetConsomme' => $budgetConsomme,
                    'budgetRestant' => $budgetRestant,
                    'tauxExecutionMoyen' => $tauxExecutionMoyen,
                ],
            ]);
    }

    public function __construct() {
        $this->middleware(function ($request, $next) {
            if( (Auth::check() && Auth::user()->role_id == 3) || (Auth::check() && Auth::user()->role_id == 4) ){ // Assurez-vous que '2' correspond à l'ID du rôle "coordinateur"
                abort(403, 'Accès interdit');
            }
            return $next($request);
        })->only(['create','store','edit','update', 'destroy']);
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Axe $axe)
    {
        $coordinateurs = User::whereHas('role', function($query) {
            $query->where('nom', 'coordinateur_axe');
        })->get();


        return inertia('Axe/Edit', [
            'axe' => new AxeResource($axe),
            'coordinateurs' => $coordinateurs,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAxeRequest $request, Axe $axe)
    {
        $data = $request->validated();
        $axe->update($data);

        $user = Auth::user();
        if ($user->role_id === 2) { // Coordinateur
            return redirect()->route('axe.mesAxes', ['coordinateur_id' => $user->id])->with('success', 'Axe mis à jour avec succès.');
        }

        return to_route('axe.index')->with('success', "Axe '$axe->nom}' modifiée avec succès");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Axe $axe)
    {
        $nom = $axe->nom;
        $axe->delete();

        $user = Auth::user();
        if ($user->role_id === 2) { // Coordinateur
            return redirect()->route('axe.mesAxes', ['coordinateur_id' => $user->id])->with('success', "Axe '$nom' supprimé avec succès.");
        }
        return redirect()->route('axe.index')->with('success', "Axe '$nom' supprimée avec succès");
    }

    public function mesAxes()
    {
        $user = Auth::user();
        $query = Axe::query();

        // Si coordinateur (role_id = 2), filtrer par coordinateur_id
        if ($user->role_id === 2) {
            $query->where('coordinateur_id', $user->id);
        }

        // Si responsable d'action (role_id = 3), filtrer les axes via les actions
        if ($user->role_id === 3) {
            $query->whereHas('sousAxes.actions', function ($q) use ($user) {
                $q->where('responsable_id', $user->id);
            });
        }

        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'asc');

        if (request('nom')) {
            $query->where('nom', 'like', '%' . request('nom') . '%');
        }

        $axes = $query->orderBy($sortField, $sortDirection)->paginate(10)->onEachSide(1);

        return inertia('Axe/Index', [
            'axes' => AxeResource::collection($axes),
            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
    }


}
