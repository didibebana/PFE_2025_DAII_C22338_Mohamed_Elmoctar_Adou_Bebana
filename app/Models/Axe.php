<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Axe extends Model
{
    /** @use HasFactory<\Database\Factories\AxeFactory> */
    use HasFactory;


    protected $fillable = [
        'nom',
        'description',
        'coordinateur_id',
    ];
    public function coordinateur(){
        return $this->belongsTo(User::class, 'coordinateur_id');
    }

    public function sousAxes(){
        return $this->hasMany(SousAxe::class);
    }


}
