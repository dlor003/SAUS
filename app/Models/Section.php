<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'personnel_id'];
    protected $table = 'sections'; // Vérifiez que cela correspond à votre nom de table


    // Relation avec le personnel (une section peut avoir un responsable)
    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'personnel_id');
    }

    // Relation avec les personnels associés à cette section (si nécessaire)
    public function personnels()
    {
        return $this->hasMany(Personnel::class);
    }
}
