<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|// Route::middleware('auth')->group(function () { });
 */

Route::get('/', [UserController::class, 'login']);
Route::get('/login', [UserController::class, 'login'])->name('login');; 
Route::post('/login_control', [UserController::class, 'login_control']);
Route::get('/forgot_password', [UserController::class, 'forgot_password']);
Route::post('/forgot_send', [UserController::class, 'forgot_send']);
Route::get('/new_pass/{id}', [UserController::class, 'new_pass']);
Route::post('/new_pass_send', [UserController::class, 'new_pass_send']);
Route::get('/register', [UserController::class, 'register']);
Route::post('/register_success', [UserController::class, 'register_success']);
Route::get('/logout', [UserController::class, 'logout']);
Route::get('/dashboard', [UserController::class, 'index']);
Route::get('/vehicles', [VehicleController::class, 'index']);
Route::get('/vehicles/add', [VehicleController::class, 'add']);
Route::post('/vehicles/add_save', [VehicleController::class, 'add_save']);
Route::get('/vehicles/edit/{id}', [VehicleController::class, 'edit']);
Route::post('/vehicles/edit_save', [VehicleController::class, 'edit_save']);
Route::get('/vehicles/delete/{id}', [VehicleController::class, 'delete']);

