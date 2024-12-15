<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fokontany extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'commune_id', 
        'code'
    ];


    // Définir le nom de la table si elle ne suit pas la convention de nommage Laravel
    protected $table = 'fokotanys';
    
     // Relation avec la commune
    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }
}
