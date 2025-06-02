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
use App\Http\Controllers\Admin\Patient\TreatmentController;
use App\Http\Controllers\Admin\Patient\AdviceController;
use App\Http\Controllers\Admin\Patient\CasesController;
use App\Http\Controllers\Admin\Patient\DocumentCOntroller;
use App\Http\Controllers\Admin\Appointment\AppointmentController;
use App\Http\Controllers\Admin\Medicine\MedicineController;
use App\Http\Controllers\Admin\Medicine\MedicineCategoryController;
use App\Http\Controllers\Admin\Prescription\PrescriptionController;
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
     Route::get('/show/{id}', 'show')->name('show');
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

Route::prefix('admin/patient')->name('admin.patient.')->controller(TreatmentController::class)->group(function () { 
    Route::get('treatments', 'index')->name('treatments.index');
    Route::post('treatments',  'store')->name('treatments.store');
    Route::get('treatments/{id}/edit', 'edit')->name('treatments.edit');
    Route::delete('treatments/{id}','destroy')->name('treatments.destroy');
});
Route::prefix('admin/patient')->name('admin.patient.')->controller(AdviceController::class)->group(function () { 
    Route::get('advice', 'index')->name('advice.index');
    Route::post('advice',  'store')->name('advice.store');
    Route::get('advice/{id}/edit', 'edit')->name('advice.edit');
    Route::delete('advice/{id}','destroy')->name('advice.destroy');
});
Route::prefix('admin/patient')->name('admin.patient.')->controller(CasesController::class)->group(function () { 
    Route::get('cases', 'index')->name('cases.index');
    Route::get('cases/create', 'create')->name('cases.create');
    Route::post('cases',  'store')->name('cases.store');
    Route::get('cases/{id}/edit', 'edit')->name('cases.edit');
    Route::put('cases/{id}/update', 'update')->name('cases.update');
    Route::delete('cases/{id}','destroy')->name('cases.destroy');
});
Route::prefix('admin/patient')->name('admin.patient.')->controller(DocumentCOntroller::class)->group(function () { 
   Route::get('documents', 'index')->name('documents.index');
    Route::post('documents', 'store')->name('documents.store');
    Route::get('documents/{id}/edit', 'edit')->name('documents.edit');
    Route::delete('documents/{id}', 'destroy')->name('documents.destroy');
    Route::get('documents/{document}/download','download')->name('documents.download');
});
Route::prefix('admin/')->name('admin.appointment.')->controller(AppointmentController::class)->group(function () { 
   Route::get('appointment', 'index')->name('index');
    Route::get('appointment/data/{status}', 'getData')->name('data');
    Route::get('appointment/create', 'create')->name('create');
    Route::post('appointment', 'store')->name('store');
    Route::get('appointment/{id}/edit', 'edit')->name('edit');
    Route::put('appointment/{appointment}/update', 'update')->name('update');
    Route::delete('appointment/{id}', 'destroy')->name('destroy');
    Route::get('/get-visit-types/{doctor}','getVisitTypes');
    Route::get('/get-visit-charge/{doctor}/{visitType}', 'getVisitCharge');
    Route::get('/schedule/slots', 'getAvailableSlots')->name('schedule.slots');
    Route::get('appointment/todays', 'todaysAppointment')->name('todays');
    Route::get('appointment/upcoming', 'upcomingAppointment')->name('upcoming');
    Route::get('appointment/calendar', 'calendar')->name('calendar');
    Route::get('appointment/request', 'requestedAppointment')->name('request');
});
Route::prefix('admin/')->name('admin.medicine.')->controller(MedicineController::class)->group(function () { 
   Route::get('medicine', 'index')->name('index');
    Route::get('medicine/create', 'create')->name('create');
    Route::post('medicine', 'store')->name('store');
    Route::get('medicine/{id}/edit', 'edit')->name('edit');
    Route::put('medicine/{medicine}/update', 'update')->name('update');
    Route::delete('medicine/{id}', 'destroy')->name('destroy');
    Route::put('medicine/{id}/update-quantity', 'updateQuantity')->name('updateQuantity');

    Route::get('/medicine/medicine-categories', [MedicineCategoryController::class,'index'])->name('medicine-categories.list');
    Route::post('/medicine/medicine-categories', [MedicineCategoryController::class,'store'])->name('medicine-categories.store');
    Route::get('/medicine/medicine-categories/{id}/edit', [MedicineCategoryController::class,'edit'])->name('medicine-categories.edit');
});

Route::prefix('admin/')->name('admin.prescription.')->controller(PrescriptionController::class)->group(function () { 
   Route::get('prescription', 'index')->name('index');
    Route::get('prescription/create', 'create')->name('create');
    Route::post('prescription', 'store')->name('store');
    Route::get('prescription/{id}/edit', 'edit')->name('edit');
    Route::put('prescription/{prescription}/update', 'update')->name('update');
    Route::delete('prescription/{id}', 'destroy')->name('destroy');
    Route::get('prescription/show/{id}', 'show')->name('show');
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
