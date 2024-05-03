<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StayController;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'getUser']);
Route::middleware('auth:sanctum')->put('/user/update-username', [AuthController::class, 'updateUsername']);


// Routes for stays
Route::middleware('auth:sanctum')->get('/user/stays', [StayController::class, 'getUserStays']);
Route::middleware('auth:sanctum')->post('/stays/create', [StayController::class, 'createStay']);
