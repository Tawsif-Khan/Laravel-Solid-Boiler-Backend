<?php

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

Route::group(['prefix' => 'v1'], function () {
    // Routes for Admins
    require __DIR__.'/api/v1/authRoutes.php';
    require __DIR__.'/api/v1/fileRoutes.php';
    require __DIR__.'/api/v1/modelRoutes.php';

    // Routes for Students
    require __DIR__.'/api/v1/student/courseRoutes.php';
    require __DIR__.'/api/v1/student/enrollmentRoutes.php';
    require __DIR__.'/api/v1/student/certificateRoutes.php';

    // Routes for Verification
    require __DIR__.'/api/v1/verificationRoutes.php';
});
