<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function getList()
    {
        $doctors = Doctor::all();
        return response()->json(['doctors' => $doctors], 200);
    }

    public function getDoctorsByMatricules(Request $request)
    {
        // Retrieve physicians' personnel numbers from the query
        $matricules = $request->input('matricules');

        // $matricules is an array
        if (!is_array($matricules)) {
            // Si $matricules n'est pas un tableau, créez-en un à partir de la valeur unique
            $matricules = [$matricules];
        }

        // Retrieve the doctors associated with the personnel numbers supplied
        $doctors = Doctor::whereIn('matricule', $matricules)->get();

        return response()->json(['doctors' => $doctors], 200);
    }
}
