<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diplome extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'personnel_id'];

    // Relation avec le personnel
    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}
