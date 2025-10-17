<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{email}', [UserController::class, 'show']);
Route::put('/users/{email}', [UserController::class, 'update']);
// Route::delete('/users/{email}', [UserController::class, 'destroy']);
