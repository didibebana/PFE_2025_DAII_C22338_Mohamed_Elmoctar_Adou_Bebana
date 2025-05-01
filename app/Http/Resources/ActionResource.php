<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
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
        'description' => $this->description,
        'statut' => $this->statut,
        'sous_axe_id' => $this->sous_axe_id,
        'sous_axe' => new SousAxeResource($this->whenLoaded('sousAxe')),
        'responsable_id' => $this->responsable_id,
        'responsable' => new UserResource($this->whenLoaded('responsable')),
        'budget' => new BudgetResource($this->whenLoaded('budget')),
        'tauxExecution' => new TauxExecutionResource($this->whenLoaded('taux_execution')),
        'date_debut' => (new Carbon($this->date_debut))->format('Y-m-d'),
        'date_fin' => (new Carbon($this->date_fin))->format('Y-m-d'),
        'created_at' => (new Carbon($this->created_at))->format('d-m-Y'),
    ];
}

}
