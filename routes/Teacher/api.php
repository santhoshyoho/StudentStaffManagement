<?php

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

use App\Http\Controllers\TeacherManagement\Authentication\AuthenticationController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('teacher')->group(function(){

    Route::post('/login',[AuthenticationController::class,'login']);
    Route::post('/ver-otp',[AuthenticationController::class,'verifyotp']);
    Route::post('/gen-otp',[AuthenticationController::class,'twoStepGenerateOtp']);
    Route::post('/ver-otp-pass',[AuthenticationController::class,'twoStepVerifyOtp']);
    Route::post('/reset-pass',[AuthenticationController::class,'resetPassword']);
    Route::post('/register',[AuthenticationController::class,'register']);
    
});
