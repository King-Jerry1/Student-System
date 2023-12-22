<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Common naming conventions for routes
// index - Show all data or students
// show - Show a single data or student
// create - Show a form to create a new user
// edit - Show a form to edit a data
// update - Update a data
// destroy - Delete a data

Route::get('/', [UserController::class, 'home']);


Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [UserController::class, 'showRegistrationForm']);
Route::post('/store', [UserController::class, 'store']);


// Google Authentication Routes
Route::get('/login/google', [UserController::class, 'redirectToGoogle']);
Route::get('/login/google/callback', [UserController::class, 'handleGoogleCallback']);

Route::controller(StudentController::class)->group(function(){
    Route::get('/dashboard', 'index')->name('admin');
    Route::get('/add/student','add');
    Route::post('/store-student-data',  'create');
    Route::get('/dashboard/student/{id}', 'show')->name('student.show');
    Route::put('/dashboard/student/update/{id}', 'update');
    
    Route::delete('/dashboard/remove-student/{id}', 'destroy');
    Route::group(['middleware' => 'auth.user'], function () {
        // Routes that require authentication 
        Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');
    });
});

// Routes for Student