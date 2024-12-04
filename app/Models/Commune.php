<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    // Relation avec le district
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    // Relation avec les fokontanies
    public function fokontanies()
    {
        return $this->hasMany(Fokontany::class);
    }


}
