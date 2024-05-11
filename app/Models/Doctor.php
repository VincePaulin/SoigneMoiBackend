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
        'avatarURL',
        'sex',
    ];

    public function stays()
    {
        return $this->hasMany(Stay::class);
    }

    public function agenda()
    {
        return $this->hasOne(Agenda::class, 'doctor_matricule', 'matricule');
    }
}
