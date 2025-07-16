<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function __construct(){
        $this->middleware(function ($request, $next) {
            if((Auth::check() && Auth::user()->role_id == 2) || (Auth::check() && Auth::user()->role_id == 3) || (Auth::check() && Auth::user()->role_id == 4) ){ // Assurez-vous que '2' correspond à l'ID du rôle "coordinateur"
                abort(403, 'Accès interdit');
            }
            return $next($request);
        })->only(['index','create','store','edit','update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = User::query();

        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'asc');

        // Filtrer par nom
        if (request('nom')) {
            $query->where('nom', 'like', '%' . request('nom') . '%');
        }

        // Filtrer par email
        if (request('email')) {
            $query->where('email', 'like', '%' . request('email') . '%');
        }

        $users = $query->with('role')  // Assurez-vous de charger les rôles des utilisateurs
                    ->orderBy($sortField, $sortDirection)
                    ->paginate(10)
                    ->onEachSide(1);

        return inertia('User/Index', [
            'users' => UserResource::collection($users),
            'queryParams' => request()->query() ?: null,
            'success' => session('success'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('User/Create',[
            'roles' => Role::all('id','nom'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['email_verified_at'] = now();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        return to_route('user.index')->with('success', 'Utilisateur ajoutée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('role', 'actions');

        return inertia('User/Show', [
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return inertia('User/Edit', [
            'user' => new UserResource($user),
            'roles' => Role::all('id','nom'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $data['email_verified_at'] = time();
        $password = $data['password'];
        if ($password) {
            $data['password'] = bcrypt($password);
        }else {
            unset($data['password']);
        }
        $user->update([
            'nom' => $data['nom'],
            'email' => $data['email'],
            'password' => $data['password'] ?? $user->password,
            'role_id' => $data['role'],
        ]);
        return to_route('user.index')->with('success', "Utilisateur \'{$user->nom}\' modifiée avec succès");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Utilisateur supprimée avec succès');
    }
}
