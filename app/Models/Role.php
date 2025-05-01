<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Les attributs que vous pouvez remplir via des méthodes comme create() ou update()
    protected $fillable = ['nom'];

    // Les colonnes qui doivent être cachées pour les tableaux ou les réponses JSON
    protected $hidden = ['created_at', 'updated_at'];

    // Les relations avec d'autres modèles
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
