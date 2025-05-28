<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\DoctorVisitController;
use App\Http\Controllers\Admin\DoctorScheduleController;
use App\Http\Controllers\Admin\DoctorHolidayController;
use App\Http\Controllers\Admin\PatientCOntroller;
use App\Http\Controllers\Admin\SymptomController;
use App\Http\Controllers\Admin\DiagnosisController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Accountant\DashboardController as AccountantDashboardController;
use App\Http\Controllers\Laboratorist\DashboardController as LaboratoristDashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Nurse\DashboardController as NurseDashboardController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/doctor/dashboard', fn() => 'Doctor Dashboard')->name('doctor.dashboard');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.index');

    Route::prefix('admin/department')->name('admin.department.')->controller(DepartmentController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/store', 'store')->name('store');
    Route::put('/{id}', 'update')->name('update');
    Route::delete('/{id}', 'destroy')->name('destroy');
  });
    Route::prefix('admin/doctor')->name('admin.doctor.')->controller(DoctorController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::put('/{id}', 'update')->name('update');
    Route::delete('/{id}', 'destroy')->name('destroy');
  });

  Route::prefix('admin/doctor')->name('admin.doctor.')->controller(DoctorVisitController::class)->group(function () {  
    Route::get('/doctor-visits','index')->name('doctor-visits.index');
    Route::post('/doctor-visits', 'store')->name('doctor-visits.store');
    Route::put('/doctor-visits/{id}', 'update')->name('doctor-visits.update');
    Route::delete('/doctor-visits/{id}', 'destroy')->name('doctor-visits.delete');
  });

   Route::prefix('admin/doctor')->name('admin.doctor.')->controller(DoctorScheduleController::class)->group(function () {  
    Route::get('/schedules','index')->name('schedules');
    Route::post('/schedules', 'store')->name('schedules.store');
    Route::delete('/schedules/{id}', 'destroy')->name('schedules.delete');
  });
   Route::prefix('admin/doctor')->name('admin.doctor.')->controller(DoctorHolidayController::class)->group(function () {  
    Route::get('/holidays','index')->name('holidays');
    Route::post('/holidays', 'store')->name('holidays.store');
     Route::put('/holidays/{id}', 'update')->name('holidays.update');
    Route::delete('/holidays/{id}', 'destroy')->name('holidays.delete');
  });
   Route::prefix('admin/patient')->name('admin.patient.')->controller(PatientCOntroller::class)->group(function () {  
    Route::get('/','index')->name('index');
    Route::post('/patient', 'store')->name('store');
    Route::get('/edit/{id}', 'edit')->name('edit');
    Route::get('/show/{id}', 'show')->name('show');
     Route::put('/update/{id}', 'update')->name('update');
    Route::delete('/delete/{id}', 'destroy')->name('delete');
  });

  Route::prefix('admin')->group(function () {
    Route::resource('symptoms', SymptomController::class)->except(['create', 'edit', 'show']);
    Route::resource('diagnoses', DiagnosisController::class)->except(['create', 'edit', 'show']);
    Route::get('diagnosis/{id}/edit', [DiagnosisController::class, 'edit'])->name('diagnoses.edit');
});




Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminDashboardController::class, 'index'])->name('superadmin.dashboard.index');
});
Route::middleware(['auth', 'role:accountant'])->group(function () {
    Route::get('/accountant/dashboard', [AccountantDashboardController::class, 'index'])->name('accountant.dashboard.index');
});
Route::middleware(['auth', 'role:laboratorist'])->group(function () {
    Route::get('/laboratorist/dashboard', [LaboratoristDashboardController::class, 'index'])->name('laboratorist.dashboard.index');
});
Route::middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/patient/dashboard', [PatientDashboardController::class, 'index'])->name('patient.dashboard.index');
});
Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard.index');
});
Route::middleware(['auth', 'role:nurse'])->group(function () {
    Route::get('/nurse/dashboard', [NurseDashboardController::class, 'index'])->name('nurse.dashboard.index');
});


});
