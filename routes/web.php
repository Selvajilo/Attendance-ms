<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SelvaController;

Route::get('/', function () {
    return view('welcomelogin');
})->name('home');

Route::get('/login/{role}', [SelvaController::class, 'login'])->name('login');

Route::post('/login/{role}', [SelvaController::class, 'adminlogin'])->name('login.submit');

Route::get('/admin/dashboard', function () {
    return view('homepage');
})->name('dashboard');

Route::get('/staff/dashboard', function () { return view('staff_dashboard'); })->name('staffdashboard');

Route::get('/staff/dashboard', [SelvaController::class, 'staffDashboard'])
    ->middleware('auth')
    ->name('staffdashboard');


Route::get('/student/dashboard', [SelvaController::class, 'showStudentDashboard'])->name('studdashboard');

Route::post('/users/store', [SelvaController::class,'storeUser'])->name('users.store');

Route::post('/students/store',[SelvaController::class,'storeStudent'])->name('students.store');

Route::post('/student/upload-photo', [SelvaController::class, 'uploadPhoto'])->name('student.upload.photo');

Route::get('/attendance/{department}', [SelvaController::class,'showAttendance'])->name('attendance.mark');

Route::post('/attendance/store', [SelvaController::class,'storeAttendance'])->name('attendance.store');

Route::get('/students/fetch', [SelvaController::class, 'fetchStudents'])->name('students.fetch'); 


Route::get('/admin/dashboard/data', [SelvaController::class, 'getData'])
->name('admin.dashboard.data');
















