<?php

use App\Http\Controllers\Authentication\AuthenticationController;
use App\Http\Controllers\Institute\Student\StudentController;
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

Route::middleware('auth:api')->group(function () {


    Route::prefix('user-management')->group(function () {
        Route::prefix('user')->group(function () {

            Route::post('create', [UserController::class, 'createUser']);
            Route::post('update', [UserController::class, 'updateUser']);
            Route::post('update-role', [UserController::class, 'updateRole']);
            Route::post('delete', [UserController::class, 'deleteUser']);
            Route::put('status', [UserController::class, 'userStatus']);
            Route::get('show', [UserController::class, 'showAllUser']);
        });


        Route::prefix('permission')->group(function () {
            Route::post('create', [UserController::class, 'createPermission']);
            Route::post('update', [UserController::class, 'updatePlatform']);
            Route::delete('delete', [UserController::class, 'deletePermission']);
            Route::get('permissions-by-id', [UserController::class, 'listByIdPermission']);
            Route::get('show', [UserController::class, 'getAllPermission']);
            Route::get('permissions-by-user-id', [UserController::class, 'getPermissionByUserId']);
        });


        Route::prefix('role')->group(function () {

            Route::post('create', [UserController::class, 'create']);
            Route::post('update', [UserController::class, 'update']);
            Route::post('delete', [UserController::class, 'delete']);
            Route::put('status', [UserController::class, 'status']);
            Route::get('show', [UserController::class, 'showRoles']);
            Route::get('user-role-by-id', [UserController::class, 'getUserRoleById']);
        });
    });
    Route::prefix('student-management')->group(function () {
        Route::post('create', [StudentController::class, 'create']);
        Route::post('update-student', [StudentController::class, 'updateStudent']);
        Route::post('list-by-id', [StudentController::class, 'listById']);
        Route::get('search', [StudentController::class, 'searchAndPagination']);
        Route::post('status-update', [StudentController::class, 'statusUpdate']);
        Route::post('get-all', [StudentController::class, 'getAll']);
    });
});

