<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutresDiplomes extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'personnel_id'
    ];

    protected $table = "autres_diplomes";


    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}

