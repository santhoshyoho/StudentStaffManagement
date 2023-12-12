<?php

use App\Http\Controllers\StudentManagement\Authentication\AuthenticationController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('student-management')->group(function () {
    //login route
    Route::post('login', [AuthenticationController::class, 'login']);
    //otp route for two step verification to change password
    Route::post('two-step-generate-otp', [AuthenticationController::class, 'twoStepGenerateOtp']);
    //checking the previous route otp by entering current otp
    Route::post('two-step-verify-otp', [AuthenticationController::class, 'twoStepVerifyOtp']);
    //changing password route
    Route::post('reset-password', [AuthenticationController::class, 'resetPassword']);
    //logout
    Route::post('logout', [AuthenticationController::class, 'logout']);
    // Show
    Route::post('register', [AuthenticationController::class, 'register']);
});