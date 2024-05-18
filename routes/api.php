<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StayController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DoctorMiddleware;

// Route for login/register
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/doctor/login', [DoctorController::class, 'login']);


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
    Route::get('/admin/user-name', [AdminController::class, 'getUserFullName']);
    Route::get('/admin/doctors', [AdminController::class, 'getAllDoctors']);
    Route::post('/admin/doctors/create', [AdminController::class, 'createDoctor']);
    Route::delete('/admin/doctor/delete/{matricule}', [AdminController::class, 'deleteDoctor']);
    Route::get('/admin/agendas', [AdminController::class, 'getAllAgendas']);
    Route::get('/admin/doctor/stays', [AdminController::class, 'getStaysByDoctorMatricule']);
    Route::get('/admin/doctor-agenda', [AdminController::class, 'getAgendaByDoctorMatricule']);
    Route::get('/admin/stay-not-programed-by-speciality', [AdminController::class, 'getStayNotProgrammedByDoctor']);
    Route::get('/admin/stay-not-programed', [AdminController::class, 'getAllStayNotProgramed']);
    Route::post('/admin/create-appointment', [AdminController::class, 'createAppointment']);
    Route::get('/admin/get-appointments-starting-today', [AdminController::class, 'getAppointmentsStartingToday']);
    Route::get('/admin/get-appointments-to-doc-starting-today', [AdminController::class, 'getAppointmentsByDoctorMatricule']);
    Route::get('/admin/get-demands-count-for-each-doctor', [AdminController::class, 'getStayCountWithNoAppointmentForEachDoctor']);
});

// Route for doctor
Route::middleware(['auth:sanctum', DoctorMiddleware::class])->group(function () {
    Route::get('/doctor/get-data', [DoctorController::class, 'getDoctorAgendaAndAppointments']);
    Route::post('/doctor/create-review', [DoctorController::class, 'createAvis']);
    Route::post('doctors/prescription', [DoctorController::class, 'createPrescription']);
});
