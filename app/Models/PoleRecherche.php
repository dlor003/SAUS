<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoleRecherche extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];
    protected $table = "poles_recherches";

    // Relation avec les personnels
    public function personnels()
    {
        return $this->belongsToMany(Personnel::class, 'personnel_poles', 'poles_id', 'personnel_id');
    }
}
