<?php

use App\Http\Controllers\AddTaskController;
use App\Models\AddTask;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('dashboard.home');
// });

Route::get('/', [AddTaskController::class, 'index']);

Route::resource('dashboard', AddTaskController::class);