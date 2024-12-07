<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;
    protected $fillable = [
        'appelation', 'nom', 'prenom', 'date_naissance', 'genre', 
        'adresse', 'nationalite', 'phone', 'mail', 'section_id', 'date_inscription'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
    
    public function cotisations()
    {
        return $this->hasMany(Cotisation::class); // une personne peut avoir plusieurs cotisations
    }

    // Définir la relation avec la table pivot
    public function typesMembres()
    {
        return $this->belongsToMany(TypeMembre::class, 'personne_type_membre');
    }


    public function isActif()
    {
        return $this->is_actif;
    }

    public function isSympathisant()
    {
        return $this->is_sympathisant;
    }

    // Relation avec la section
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // Relation avec les diplômes
    public function diplomes()
    {
        return $this->hasMany(Diplome::class);
    }

    // Relation avec les pôles de recherche
    public function polesRecherche()
    {
        return $this->hasMany(PoleRecherche::class);
    }

    // Relation avec les activités individuelles
    public function activiteIndividual()
    {
        return $this->hasMany(ActiviteIndividual::class);
    }
}
