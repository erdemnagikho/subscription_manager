<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\DeviceController;
use App\Http\Controllers\api\SubscriptionController;

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'devices'], function () {
        Route::post('', [DeviceController::class, 'store']);
    });

    Route::group(['prefix' => 'subscriptions'], function () {
        Route::post('', [SubscriptionController::class, 'store']);
        Route::get('{token}', [SubscriptionController::class, 'checkSubscription']);
    });
});
