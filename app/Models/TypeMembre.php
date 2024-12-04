<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeMembre extends Model
{
    use HasFactory;

    protected $fillable = ['type']; // "actif", "sympathisant"

    // Définir la relation avec la table pivot
    public function personnes()
    {
        return $this->belongsToMany(Personnel::class, 'personne_type_membre');
    }
}
