<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiviteIndividual extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'personnel_id', 'domain'];

    // Relation avec le personnel
    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}
