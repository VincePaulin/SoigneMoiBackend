<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    protected $fillable = ['libelle', 'date', 'description', 'doctor_id', 'patient_id'];

    /**
     * Get the doctor associated with the avis.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the patient associated with the avis.
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
