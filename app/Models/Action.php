<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SousAxe;

class Action extends Model
{
    /** @use HasFactory<\Database\Factories\ActionFactory> */
    use HasFactory;

    /**
     * Get all of thn responsableaction for the Action
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    protected $fillable = [
        'nom',
        'description',
        'statut',
        'date_debut',
        'date_fin',
        'sous_axe_id',
        'responsable_id',
        'montant',
    ];

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function sousAxe()
    {
        return $this->belongsTo(SousAxe::class);  // Chaque action appartient à un SousAxe
    }

    public function budget()
    {
        return $this->hasOne(Budget::class);  // Chaque action a un seul budget
    }
    

    public function tauxExecutions()
    {
        return $this->hasMany(TauxExecution::class, 'action_id');
    }

}
