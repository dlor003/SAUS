<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoleRecherche extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'personnel_id'];
    protected $table = "poles_recherches";

    // Relation avec le personnel
    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}
