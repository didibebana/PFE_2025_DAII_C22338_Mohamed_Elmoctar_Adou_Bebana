<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Http\Requests\StoreActionRequest;
use App\Http\Requests\UpdateActionRequest;
use App\Http\Resources\ActionResource;
use App\Models\Budget;
use App\Models\Log;
use App\Models\SousAxe;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Inertia\Inertia;

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role_id !== 1) {
            abort(403, 'Accès interdit - Administrateur uniquement');
        }

        $query = Action::query();



        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'asc');

        if (request('nom')) {
            $query->where('nom', 'like', '%' . request('nom') . '%');
        }
        if(request('statut')  && request('statut') !== 'Tous'){
            $query->where('statut', request('statut'));
        }

        $actions = $query->with('sousAxe')->orderBy($sortField, $sortDirection)->paginate(10)->onEachSide(1);
        //dd($actions);
        return inertia('Action/Index', [
            'actions' => ActionResource::collection($actions),
            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $responsables = User::whereHas('role', function($query) {
            $query->where('nom', 'responsable_action');
        })->get();

        $user = Auth::user();

    // Récupération des sous-axes selon le rôle de l'utilisateur
        if ($user->role->nom === 'coordinateur_axe') {
            // Si coordinateur, récupérer uniquement les sous-axes liés à lui via l'axe
            $sousaxes = SousAxe::whereHas('axe', function ($query) use ($user) {
                $query->where('coordinateur_id', $user->id);
        })->get();
        } else {
            // Si admin ou autre rôle, récupérer tous les sous-axes
            $sousaxes = SousAxe::all();
        }



        return inertia('Action/Create', [
            'sousaxes' => $sousaxes,
            'responsables' => $responsables,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActionRequest $request)
    {

        // dd($request->all());
        $data = $request->validated();
        $responsable = User::find($data['responsable_id']);

        if (!$responsable || !$responsable->role || $responsable->role->nom !== 'responsable_action') {
            return redirect()->back()->withErrors(['error' => 'L\'utilisateur sélectionné n\'est pas un responsable d\'action.']);
        }
        $action = Action::create($data);
        if ($request->filled('taux')) {
            $action->tauxExecutions()->create([
                'taux' => $request->input('taux'),
            ]);
        }
        if ($request->has('montant_budget')) {
            $budget = new Budget([
                'montant' => $request->montant_budget,
                'date_allocation' => now(),
                // Autres informations du budget
            ]);
            $action->budget()->save($budget);  // On associe le budget à l'action
        }
        $user = Auth::user();
        if ($user->role_id === 2) { // Responsable d'action
            return redirect()->route('action.mesActions', ['responsable_id' => $user->id])->with('success', 'Action ajoutée avec succès.');
        }

        Log::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'entity' => 'action',
            'description' => 'Création de l’action : ' . $action->nom,
        ]);


        return to_route('action.index')->with('success', 'Action ajoutée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Action $action)
    {
        //$query = $action->responsable();

        // $sortField = request('sort_field', 'created_at');
        // $sortDirection = request('sort_direction', 'asc');

        // if (request('nom')) {
        //     //$query->where('nom', 'like', '%' . request('nom') . '%');
        // }
        $action->load('sousAxe', 'responsable', 'budget', 'tauxExecutions');

        $lastTauxExecution = $action->tauxExecutions()->latest()->first();

        $montantConsomme = (($action->budget->montant ?? 0) * ($lastTauxExecution->taux ?? 0) / 100);

        $resteAConsommer = ($action->budget->montant ?? 0) - $montantConsomme;

        //$responsables = $query->with('action')->orderBy($sortField, $sortDirection)->paginate(10)->onEachSide(1);

        return inertia('Action/Show',[
                'action' => new ActionResource($action),
                'queryParams' => request()->query() ?: null,
                'montantConsomme' => $montantConsomme,
                'resteAConsommer' => $resteAConsommer,
                'taux_execution' => $lastTauxExecution,
            ]);
    }

    public function __construct()
    {
        // Vous pouvez restreindre l'accès à certaines méthodes en fonction du rôle
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->role_id == 2) { // Assurez-vous que '2' correspond à l'ID du rôle "coordinateur"
                abort(403, 'Accès interdit');
            }
            return $next($request);
        })->only(['edit','update', 'destroy']); // Mettez ici les métodes que vous souhaitez restreindre
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->role_id == 3) {
                abort(403, 'Accès interdit');
            }
            return $next($request);
        })->only(['create','store']);
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->role_id == 4) {
                abort(403, 'Accès interdit');
            }
            return $next($request);
        })->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Action $action)
    {
        $lastTauxExecution = $action->tauxExecutions()->latest()->first();
        $responsables = User::whereHas('role', function($query) {
            $query->where('nom', 'responsable_action');
        })->get();

        return inertia('Action/Edit', [
            'action' => new ActionResource($action),
            'sousaxes' => SousAxe::all(['id', 'nom']),
            'responsables' => $responsables,
            'taux_execution' => $lastTauxExecution,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActionRequest $request, Action $action)
    {
        $data = $request->validated();
        if ($request->filled('taux')) {
            $action->tauxExecutions()->create([
                'taux' => $request->input('taux'),
            ]);
        }


        $action->update($data);

        $user = Auth::user();
        if ($user->role_id === 3) { // Responsable d'action
            return redirect()->route('responsable.action.mesActions', ['responsable_id' => $user->id])->with('success', 'Action mise à jour avec succès.');
        }
        Log::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'entity' => 'budget',
            'description' => 'Modification du budget de l/'.'action  : ' . $action->nom,
        ]);

        return to_route('action.index')->with('success', "Action \'{$action->nom}\' modifiée avec succès");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Action $action)
    {
        $nom = $action->nom;
        $action->delete();

        $user = Auth::user();
        if ($user->role_id === 3) { // Responsable d'action
            return redirect()->route('action.mesActions', ['responsable_id' => $user->id])->with('success', "Action '$nom' supprimée avec succès.");
        }

        Log::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'entity' => 'action',
            'description' => 'Suppression d/' . 'action : ' . $action->nom,
        ]);

        return redirect()->route('action.index')->with('success', "Action \'\'{$nom}\'\' supprimée avec succès");
    }

    public function mesActions()
{
    $user = Auth::user();
    $query = Action::query();

    // Si coordinateur
    if ($user->role_id === 2) {
        $query->whereHas('sousAxe.axe', function ($q) use ($user) {
            $q->where('coordinateur_id', $user->id);
        });
    }

    // Si responsable
    if ($user->role_id === 3) {
        $query->where('responsable_id', $user->id);
    }

    $sortField = request('sort_field', 'created_at');
    $sortDirection = request('sort_direction', 'asc');

    if (request('nom')) {
        $query->where('nom', 'like', '%' . request('nom') . '%');
    }

    if (request('statut') && request('statut') !== 'Tous') {
        $query->where('statut', request('statut'));
    }

    $actions = $query->with('sousAxe')->orderBy($sortField, $sortDirection)
                     ->paginate(10)
                     ->onEachSide(1);

    return inertia('Action/Index', [
        'actions' => ActionResource::collection($actions),
        'queryParams' => request()->query() ?: null,
        'success' => session('success'),
    ]);
}


}
