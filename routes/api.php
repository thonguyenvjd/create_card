<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
});

// Template
Route::get('templates/download-csv/{filename}', [\App\Http\Controllers\TemplateController::class, 'downloadCsv'])->name('templates.download-csv');

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
            Route::get('me', 'me')->name('.me');
            Route::post('logout', 'logout')->name('.logout');
        });
    });

    // Template
    Route::get('templates/jobs', [\App\Http\Controllers\TemplateController::class, 'jobs'])->name('templates.jobs');
    Route::post('templates/import', [\App\Http\Controllers\TemplateController::class, 'import'])->name('templates.import');
    Route::apiResource('templates', \App\Http\Controllers\TemplateController::class);

    // Asset
    Route::apiResource('assets', \App\Http\Controllers\AssetController::class);
});
