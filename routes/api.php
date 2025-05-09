<?php

use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\EnrolledPerCourseController;
use App\Http\Controllers\API\RegistrationControlle;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\TeacherController;
use App\Http\Controllers\AuthController;
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
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware(['jwt.auth', 'jwt.blacklist'])->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
});

Route::apiResource('courses', CourseController::class);
Route::apiResource('students', StudentController::class);
Route::apiResource('registrations', RegistrationControlle::class);
Route::apiResource('teachers', TeacherController::class);
Route::apiResource('enrolledPerCourse', EnrolledPerCourseController::class);