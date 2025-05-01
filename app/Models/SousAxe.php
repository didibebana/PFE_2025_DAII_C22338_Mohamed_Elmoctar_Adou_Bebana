<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousAxe extends Model
{
    /** @use HasFactory<\Database\Factories\SousAxeFactory> */
    use HasFactory;

    protected $fillable = [
        'nom',
        'axe_id',
        'description'
    ];
    public function axe()
    {
        return $this->belongsTo(Axe::class);  // Relation inverse avec Axe
    }

    public function actions()
    {
        return $this->hasMany(Action::class);  // Un sous-axe peut avoir plusieurs actions
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);  // Relation avec Budget
    }
}
