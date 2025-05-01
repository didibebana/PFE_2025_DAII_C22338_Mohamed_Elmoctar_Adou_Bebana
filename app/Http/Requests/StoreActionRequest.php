<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'statut' => ['required', Rule::in(['En attente', 'En cours', 'Terminée', 'A venir', 'Annulée', 'En retard'])],
            'date_debut' => ['required','date'],
            'date_fin' => ['required','date','after_or_equal:date_debut'],
            'sous_axe_id' => ['required','exists:sous_axes,id'],
            'responsable_id' => ['required','exists:users,id'],
            'taux' => ['nullable','numeric','min:0','max:100'],
        ];
    }
}
