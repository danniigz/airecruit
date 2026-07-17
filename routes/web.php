<?php

use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Placeholders: se implementarán en próximas tareas del MVP.
    Route::get('/profile', function () {
        return view('coming-soon', ['title' => 'Perfil profesional']);
    })->name('profile.index');

    Route::get('/cvs', function () {
        return view('coming-soon', ['title' => 'CVs']);
    })->name('cvs.index');

    Route::get('/job-offers', function () {
        return view('coming-soon', ['title' => 'Ofertas de empleo']);
    })->name('job-offers.index');

    Route::get('/cover-letters', function () {
        return view('coming-soon', ['title' => 'Cartas de presentación']);
    })->name('cover-letters.index');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings', [SettingsController::class, 'destroy'])->name('settings.destroy');
});

require __DIR__.'/auth.php';
