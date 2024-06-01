<?php

use App\Http\ApiV1\Modules\Banners\Controllers\BannersController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->group(function () {
    Route::middleware(['simple-jwt', 'banner-owner'])->group(function () {
        Route::apiResource('banners', BannersController::class)->except(['index', 'show']);
        Route::get('banners/views', [BannersController::class, 'showBannerViews']);
    });

    Route::middleware(['simple-jwt'])->group(function () {
        Route::post('banners/{id}/show', [BannersController::class, 'showBanner']);
    });

    Route::middleware(['simple-jwt'])->group(function () {
        Route::get('banners', [BannersController::class, 'index']);
        Route::get('banners/{id}', [BannersController::class, 'show']);
    });
});

