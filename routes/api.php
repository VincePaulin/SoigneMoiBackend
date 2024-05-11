<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StayController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'getUser']);
Route::middleware('auth:sanctum')->put('/user/update-username', [AuthController::class, 'updateUsername']);


// Routes for stays
Route::middleware('auth:sanctum')->get('/user/stays', [StayController::class, 'getUserStays']);
Route::middleware('auth:sanctum')->post('/stays/create', [StayController::class, 'createStay']);

// Route for doctors
Route::get('/doctors/list', [DoctorController::class, 'getList']);
Route::middleware('auth:sanctum')->post('/stay/doctors', [DoctorController::class, 'getDoctorsByMatricules']);

// Route for admin
Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    Route::get('/admin/doctors', [AdminController::class, 'getAllDoctors']);
    Route::post('/admin/doctors/create', [AdminController::class, 'createDoctor']);
    Route::delete('/admin/doctor/delete/{matricule}', [AdminController::class, 'deleteDoctor']);
    Route::get('/admin/agendas', [AdminController::class, 'getAllAgendas']);
});
