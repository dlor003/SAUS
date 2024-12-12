<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiviteIndividual extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    // Relation avec le personnel
    public function personnels()
    {
        return $this->belongsToMany(Personnel::class, 'personnel_activite');
    }
}
