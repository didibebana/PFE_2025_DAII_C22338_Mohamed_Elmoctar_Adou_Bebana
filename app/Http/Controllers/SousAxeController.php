<?php

namespace App\Http\Controllers;

use App\Models\SousAxe;
use App\Http\Resources\SousAxeResource;
use App\Http\Requests\StoreSousAxeRequest;
use App\Http\Requests\UpdateSousAxeRequest;
use App\Http\Resources\ActionResource;
use App\Models\Axe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class SousAxeController extends Controller
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
        
        $query = SousAxe::query();

        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'asc');

        if (request('nom')) {
            $query->where('nom', 'like', '%' . request('nom') . '%');
        }

        $sousaxes = $query->with('axe')->orderBy($sortField, $sortDirection)->paginate(10)->onEachSide(1);

        return inertia('SousAxe/Index', [
            'sousaxes' => SousAxeResource::collection($sousaxes),
            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('SousAxe/Create',[
            'axes' => Axe::all('id','nom'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSousAxeRequest $request)
    {
        $data = $request->validated();
        SousAxe::create($data);

        $user = Auth::user();
        if ($user->role_id === 2) { // Coordinateur
            $axeIds = Axe::where('coordinateur_id', $user->id)->pluck('id');
            return redirect()->route('sousaxe.mesSousAxes', ['axe_ids' => $axeIds])->with('success', 'Sous-Axe ajouté avec succès.');
        }

        return redirect()->route('sousaxe.index')->with('success', 'Sous Axe ajoutée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sousaxe $sousaxe)
    {
        $query = $sousaxe->Actions();

        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'asc');

        if (request('nom')) {
            $query->where('nom', 'like', '%' . request('nom') . '%');
        }
        if(request('statut')  && request('statut') !== 'Tous'){
            $query->where('statut', request('statut'));
        }
        $sousaxe->load('axe');

        $actions = $query->with('sousAxe')->orderBy($sortField, $sortDirection)->paginate(10)->onEachSide(1);
        //dd($actions);
        return inertia('SousAxe/Show', [
            'sousaxe' => new SousAxeResource($sousaxe),
            'actions' => ActionResource::collection($actions),
            'queryParams' => request()->query() ?: null,
        ]);
    }

    public function __construct(){
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
    public function edit(SousAxe $sousaxe)
    {
        return inertia('SousAxe/Edit', [
            'sousaxe' => new SousAxeResource($sousaxe),
            'axes' => Axe::all(['id','nom']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSousAxeRequest $request, SousAxe $sousAxe)
    {
        $data = $request->validated();
        $sousAxe->update($data);

        $user = Auth::user();
        if ($user->role_id === 2) { // Coordinateur
            $axeIds = Axe::where('coordinateur_id', $user->id)->pluck('id');
            return redirect()->route('sousaxe.mesSousAxes', ['axe_ids' => $axeIds])->with('success', 'Sous-Axe mis à jour avec succès.');
        }

        return redirect()->route('sousaxe.index')->with('success', "Sous Axe \'{$sousAxe->nom}\' modifiée avec succès");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SousAxe $sousAxe)
    {
        $nom = $sousAxe->nom;
        $sousAxe->delete();

        $user = Auth::user();
        if ($user->role_id === 2) { // Coordinateur
            $axeIds = Axe::where('coordinateur_id', $user->id)->pluck('id');
            return redirect()->route('sousaxe.mesSousAxes', ['axe_ids' => $axeIds])->with('success', "Sous-Axe '$nom' supprimé avec succès.");
        }

        return redirect()->route('sousaxe.index')->with('success', "Sous Axe \'\'{$nom}\'\' supprimée avec succès");
    }

    public function mesSousAxes()
    {
        $user = Auth::user();
        $query = SousAxe::query();

        // Si coordinateur
        if ($user->role_id === 2) {
            $axeIds = Axe::where('coordinateur_id', $user->id)->pluck('id');
            $query->whereIn('axe_id', $axeIds);
        }

        // Si responsable
        if ($user->role_id === 3) {
            $query->whereHas('actions', function ($q) use ($user) {
                $q->where('responsable_id', $user->id);
            });
        }

        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'asc');

        if (request('nom')) {
            $query->where('nom', 'like', '%' . request('nom') . '%');
        }

        $sousAxes = $query->with('axe')->orderBy($sortField, $sortDirection)
                        ->paginate(10)
                        ->onEachSide(1);

        return inertia('SousAxe/Index', [
            'sousaxes' => SousAxeResource::collection($sousAxes),
            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
    }

}
