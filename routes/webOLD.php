<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MapController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Admin\UserController;

/**
 * Root: masuk langsung ke peta
 */
Route::get('/', fn () => redirect()->route('map'));

/**
 * Breeze Livewire biasanya redirect ke route('dashboard') setelah login.
 * Kita arahkan dashboard ke map supaya konsisten.
 */
Route::get('/dashboard', fn () => redirect()->route('map'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/map', [MapController::class, 'index'])->name('map');

    // AJAX endpoints (session-auth)
    Route::prefix('ajax')->name('ajax.')->group(function () {
        Route::get('/locations', [\App\Http\Controllers\Ajax\LocationAjaxController::class, 'index'])
            ->middleware('permission:locations.view')->name('locations');

        Route::get('/locations/check', [\App\Http\Controllers\Ajax\LocationAjaxController::class, 'checkExact'])
            ->middleware('permission:locations.view')->name('check');

        Route::get('/locations/nop-available', [\App\Http\Controllers\Ajax\LocationAjaxController::class, 'nopAvailable'])
            ->middleware('permission:locations.view')->name('nop_available');

        Route::post('/locations/bulk-check', [\App\Http\Controllers\Ajax\LocationAjaxController::class, 'bulkCheck'])
            ->middleware('permission:locations.view')->name('bulk_check');

        Route::get('/geocode/reverse', [\App\Http\Controllers\Ajax\GeocodingAjaxController::class, 'reverse'])
            ->middleware('permission:locations.view')->name('reverse');
    });

    // Locations CRUD + approval
    Route::prefix('locations')->name('locations.')->group(function () {
        Route::post('/', [LocationController::class, 'store'])
            ->middleware('permission:locations.create')
            ->name('store');

        Route::get('/{location}', [LocationController::class, 'show'])
            ->middleware('permission:locations.view')
            ->name('show');

        Route::put('/{location}', [LocationController::class, 'update'])
            ->middleware('permission:locations.edit')
            ->name('update');

        Route::delete('/{location}', [LocationController::class, 'destroy'])
            ->middleware('permission:locations.delete')
            ->name('destroy');

        Route::post('/{location}/submit', [LocationController::class, 'submit'])
            ->middleware('permission:locations.submit')
            ->name('submit');

        Route::post('/{location}/approve', [LocationController::class, 'approve'])
            ->middleware('permission:locations.approve')
            ->name('approve');

        Route::post('/{location}/unpublish', [LocationController::class, 'unpublish'])
            ->middleware('permission:locations.approve')
            ->name('unpublish');
    });

    // Import
    Route::get('/import', [ImportController::class, 'index'])
        ->middleware('permission:imports.create')
        ->name('import.index');

    // Exports
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/excel', [ExportController::class, 'excel'])->middleware('permission:exports.run')->name('excel');
        Route::get('/csv', [ExportController::class, 'csv'])->middleware('permission:exports.run')->name('csv');
        Route::get('/geojson', [ExportController::class, 'geojson'])->middleware('permission:exports.run')->name('geojson');
        Route::get('/pdf', [ExportController::class, 'pdf'])->middleware('permission:exports.run')->name('pdf');
    });

    // Admin
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });
});

require __DIR__ . '/auth.php';
