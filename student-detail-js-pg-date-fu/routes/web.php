<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('students.index');
});

Route::get('students/data', [StudentController::class, 'data'])->name('students.data');
Route::resource('students', StudentController::class);

//Route::get('/student', [StudentController::class, 'index'])->name('student.index');
//Route::get('/create', [StudentController::class, 'store'])->name('student.create');