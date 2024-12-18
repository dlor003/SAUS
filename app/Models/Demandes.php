<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demandes extends Model
{
    use HasFactory;

    protected $fillable = [
        'personnel_id', 'type_demande', 'message', 'status', 'admin_message'
    ];

    // Relation avec le modèle Personnel (ou Utilisateur)
    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'personnel_id');
    }
}
