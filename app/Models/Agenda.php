<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_matricule',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_matricule', 'matricule');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
