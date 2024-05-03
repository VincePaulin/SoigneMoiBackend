<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stay;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullName',
        'specialty',
        'medicalSections',
        'matricule',
    ];

    public function stays()
    {
        return $this->hasMany(Stay::class);
    }
}
