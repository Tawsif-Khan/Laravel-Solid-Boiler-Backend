<?php

use App\Http\Controllers\Api\V1\File\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::post('file', FileUploadController::class);
