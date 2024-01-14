<?php

use App\Http\Controllers\Api\V1\Model\DeleteController;
use App\Http\Controllers\Api\V1\Model\DropdownController;
use App\Http\Controllers\Api\V1\Model\GetController;
use App\Http\Controllers\Api\V1\Model\StoreController;
use App\Http\Controllers\Api\V1\Model\UpdateController;
use App\Http\Controllers\Api\V1\Model\ViewController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'models'], function () {
    Route::post('dropdown', DropdownController::class);
    Route::post('get', GetController::class);
    Route::post('view/{id}', ViewController::class);
    Route::post('store', StoreController::class);
    Route::post('update/{id}', UpdateController::class);
    Route::post('delete/{id}', DeleteController::class);
});
