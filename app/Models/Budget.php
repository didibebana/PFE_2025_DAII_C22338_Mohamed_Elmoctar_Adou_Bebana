<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{

    protected $fillable = [
        'montant',
        'date_allocation',
    ];
    /** @use HasFactory<\Database\Factories\BudgetFactory> */
    use HasFactory;


    public function action()
    {
        return $this->belongsTo(Action::class);  // Relation avec Action (un budget pour une action)
    }
}
