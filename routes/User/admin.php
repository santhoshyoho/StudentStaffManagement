<?php

use App\Http\Controllers\Institute\InstituteController;
use App\Http\Controllers\InstituteManagement\Authentication\AuthenticationController;
// use App\Http\Controllers\InstituteManagement\Authentication\AuthenticationController;
use App\Http\Controllers\UserManagement\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('users')->group(function () {
    //login route
    Route::post('/login', [AuthenticationController::class, 'login']);
    //generate otp
    Route::post('/generate-otp', [AuthenticationController::class, 'generateOtp']);
    //otp route for two step notification enabled
    Route::post('/ver-otp', [AuthenticationController::class, 'verifyOtp']);
    //otp route for two step verification to change password
    Route::post('/ver-otp-pass', [AuthenticationController::class, 'passwordResetVerifyotp']);
    //changing password route
    Route::post('/reset-pass', [AuthenticationController::class, 'resetPassword']);
});
Route::prefix('user-management')->group(function () {
    Route::prefix('user')->group(function () {

        Route::post('create', [UserController::class, 'createUser']);
        Route::post('update', [UserController::class, 'updateUser']);
        Route::post('update-role', [UserController::class, 'updateRole']);
        Route::post('delete', [UserController::class, 'deleteUser']);
        Route::put('status', [UserController::class, 'userStatus']);
        Route::get('show', [UserController::class, 'showAllUser']);
    });
});
   


