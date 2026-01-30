<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ImportController;

Route::get('/', function () {
    return redirect()->route('map');
});

// --- GUEST ACCESSIBLE ROUTES (Read-Only) ---

// Halaman Map (Guest boleh lihat)
Route::get('/map', [MapController::class, 'index'])->name('map');

// Halaman Data Lokasi List (Guest boleh lihat)
Route::get('/locations', \App\Livewire\Locations\LocationList::class)->name('locations.index');

// Ajax Accessible by Guest
Route::prefix('ajax')->name('ajax.')->group(function () {
    Route::get('/locations', [MapController::class, 'ajaxLocations'])->name('locations');
    Route::get('/locations/check', [MapController::class, 'ajaxCheckExact'])->name('locations.check');
});

// --- AUTHENTICATED ROUTES ---
Route::middleware(['auth', 'verified'])->group(function () {

    // --- Locations Management (Write Access) ---
    // tambah lokasi manual (1 data)
    Route::get('/locations/create', [MapController::class, 'createLocation'])->name('locations.create');
    Route::post('/locations', [MapController::class, 'storeLocation'])->name('locations.store');

    // edit lokasi
    Route::get('/locations/{id}/edit', [MapController::class, 'editLocation'])->name('locations.edit');
    Route::put('/locations/{id}', [MapController::class, 'updateLocation'])->name('locations.update');

    // Ajax Protected actions
    Route::prefix('ajax')->name('ajax.')->group(function () {
        Route::delete('/locations/{id}', [MapController::class, 'ajaxDeleteLocation'])->name('locations.delete');
    });

    // --- Import ---
    Route::get('/import', [ImportController::class, 'index'])
        ->middleware(['role:super-admin|admin'])
        ->name('import.index');

    // alias biar nggak 404 kalau ada yang keburu buka ini
    Route::get('/locations/import', fn () => redirect()->route('import.index'));

    // --- User Management ---
    Route::get('/profile', \App\Livewire\User\UserProfile::class)->name('profile');
    
    // Super Admin & Admin Area
    Route::middleware(['role:super-admin|admin'])->group(function () {
        Route::get('/users', \App\Livewire\Admin\UserList::class)->name('users.index');
    });

    // Super Admin Only Area (Role Management)
    Route::middleware(['role:super-admin'])->group(function () {
        Route::get('/roles', \App\Livewire\Admin\RoleList::class)->name('roles.index');
    });
});

require __DIR__.'/auth.php';
