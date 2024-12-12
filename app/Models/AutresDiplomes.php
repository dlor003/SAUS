<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutresDiplomes extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    protected $table = "autres_diplomes";


    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}

