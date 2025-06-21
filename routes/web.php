<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\staffController;

// Route for login form
Route::get('/', [LoginController::class, 'auth'])->name('login');

// Route to handle form submission (no database yet)
Route::post('/login', [LoginController::class, 'handleLogin']);

// Route to dashboard
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');
Route::get('/dashboards', [adminController::class, 'dashboards'])->name('dashboards');

// Appointment page route
Route::get('/appointments', [adminController::class, 'appointments'])->name('appointments');

// Patient page route
Route::get('/patients', [adminController::class, 'patients'])->name('patients');

// Staff page route
Route::get('/staffs', [adminController::class, 'staffs'])->name('staffs');

// Medication page route
Route::get('/medications', [adminController::class, 'medications'])->name('medications');

// Reports page route
Route::get('/reports', [adminController::class, 'reports'])->name('reports');

// Settings page route
Route::get('/settings', [adminController::class, 'settings'])->name('settings');

// Staff Home page route
Route::get('/homes', [staffController::class, 'homes'])->name('homes');

// Staff- Patient page route
Route::get('/staffPatients', [staffController::class, 'staffPatients'])->name('staffPatients');

// Staff- Appointment page route
Route::get('/staffAppointments', [staffController::class, 'staffAppointments'])->name('staffAppointments');

// Staff- Bill page route
Route::get('/bills', [staffController::class, 'bills'])->name('bills');

