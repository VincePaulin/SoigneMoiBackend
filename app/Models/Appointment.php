<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'patient_id',
        'doctor_matricule',
        'stay_id',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
