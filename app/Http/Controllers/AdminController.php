<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAllDoctors()
    {
        $doctors = Doctor::all();
        return response()->json(['doctors' => $doctors], 200);
    }
}
