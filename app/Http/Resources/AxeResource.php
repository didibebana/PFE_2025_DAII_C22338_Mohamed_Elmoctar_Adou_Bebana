<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AxeResource extends JsonResource
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
            'coordinateur_id' => $this->coordinateur_id,
            'coordinateur' => new UserResource($this->whenLoaded('coordinateur')),
            'created_at' => (new Carbon($this->created_at))->format('d-m-Y'),
        ];
    }
}
