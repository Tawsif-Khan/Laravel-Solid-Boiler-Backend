<?php

use App\Http\Controllers\Api\V1\Verification\CertificateVerificationController;
use App\Http\Controllers\Api\V1\Verification\OtpController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'verifications'], function () {
    Route::post('otp', [OtpController::class, 'send']);
    Route::post('otp/verify', [OtpController::class, 'verify']);

    Route::post('certificate', CertificateVerificationController::class);
});
