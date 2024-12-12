<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;
    protected $fillable = [
        'appelation', 'nom', 'prenom', 'date_naissance', 'genre', 
        'adresse', 'nationalite', 'phone', 'mail', 'section_id', 'date_inscription', 'profile_picture'
    ];

    public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture 
        ? asset(str_replace('public/', 'storage/', $this->profile_picture))
        : null;   
    }

    public function autresDiplomes()
    {
        return $this->hasMany(AutresDiplomes::class);
    }


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
        return $this->belongsToMany(Diplome::class, 'personnel_diplome');
    }


  // Relation avec les pôles de recherche
    public function polesRecherche()
    {
        return $this->belongsToMany(PoleRecherche::class, 'personnel_poles', 'personnel_id', 'poles_id');
    }
    // Relation avec les activités individuelles
    public function activiteIndividual()
    {
        return $this->belongsToMany(ActiviteIndividual::class, 'personnel_activite');
    }
}
