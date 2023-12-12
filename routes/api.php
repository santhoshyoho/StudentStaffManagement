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

use App\Http\Controllers\Institute\InstituteController;
// use App\Http\Controllers\InstituteManagement\Authentication\AuthenticationController;
use App\Http\Controllers\TeacherManagement\Authentication\AuthenticationController;
use App\Http\Controllers\UserManagement\UserController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::prefix('users')->group(function () {
//     //login route
//     Route::post('/login', [AuthenticationController::class, 'login']);
//     //generate otp
//     Route::post('/generate-otp', [AuthenticationController::class, 'generateOtp']);
//     //otp route for two step notification enabled
//     Route::post('/ver-otp', [AuthenticationController::class, 'verifyOtp']);
//     //otp route for two step verification to change password
//     Route::post('/ver-otp-pass', [AuthenticationController::class, 'passwordResetVerifyotp']);
//     //changing password route
//     Route::post('/reset-pass', [AuthenticationController::class, 'resetPassword']);
// });

// Route::middleware('auth:api')->group(function () {


    Route::prefix('user-management')->group(function () {
        
        Route::post('register',[UserController::class,'register']);
        Route::post('login',[UserController::class,'login']);
        Route::post('verifyOtp',[UserController::class,'verifyOtp']);
        Route::post('generateOtp',[UserController::class,'generateOtp']);
        Route::post('passwordResetVerifyotp',[UserController::class,'passwordResetVerifyotp']);
        Route::post('resetPassword',[UserController::class,'resetPassword']);
          


     
   
});

// });

