<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotisation extends Model
{
    use HasFactory;
    protected $fillable = ['personnel_id', 'name', 'date_payment', 'preuve_picture'];

    public function personne()
    {
        return $this->belongsTo(Personnel::class); // une cotisation appartient à une personne
    }
}
