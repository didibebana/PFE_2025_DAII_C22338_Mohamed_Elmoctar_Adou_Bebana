<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TauxExecution extends Model
{
    protected $fillable = [
        'taux',
        
    ];
    /** @use HasFactory<\Database\Factories\TauxExecutionFactory> */
    use HasFactory;

    public function action()
    {
        return $this->belongsTo(Action::class, 'action_id');
    }
}
