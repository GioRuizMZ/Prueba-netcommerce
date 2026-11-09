<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/companies', [CompanyController::class, 'index']);
Route::post('/tasks/create', [TaskController::class, 'store']);
