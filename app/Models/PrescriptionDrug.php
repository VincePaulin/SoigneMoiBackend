<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionDrug extends Model
{
    use HasFactory;

    protected $fillable = ['prescription_id', 'drug', 'dosage'];

    protected $table = 'prescription_drug';

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
