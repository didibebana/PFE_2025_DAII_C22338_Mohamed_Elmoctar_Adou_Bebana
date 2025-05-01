<?php

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'email' => $this->email,
            'role_id' => $this->role->id ?? null,
            'role_nom' => $this->role->nom ?? 'Aucun rôle',
            'role' => new RoleResource($this->whenLoaded('role')),
            'actions' => ActionResource::collection($this->whenLoaded('actions')),

        ];
    }
}
