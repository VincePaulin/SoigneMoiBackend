<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stay;

class StayController extends Controller
{
    // Fonction pour récupérer les séjours d'un utilisateur spécifique
    public function getUserStays(Request $request)
    {
        $user = $request->user();
        // Récupérer les séjours de l'utilisateur à partir de son ID
        $stays = Stay::where('user_id', $user->id)->get();
        return response()->json(['stays' => $stays], 200);
    }
}
