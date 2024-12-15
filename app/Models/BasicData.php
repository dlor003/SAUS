<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicData extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'district_attempts',
        'commune_attempts',
        'fokontany_attempts',
        'is_blocked'
    ];

     // Relation : BasicData a un seul personnel
     public function personnel()
     {
         return $this->hasOne(Personnel::class);
     }
}
