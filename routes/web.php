<?php

use App\Http\Controllers\Profile\CertificationController;
use App\Http\Controllers\Profile\EducationController;
use App\Http\Controllers\Profile\ExperienceController;
use App\Http\Controllers\Profile\LanguageController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\SkillController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');

        Route::post('/experiences', [ExperienceController::class, 'store'])->name('experiences.store');
        Route::patch('/experiences/{experience}', [ExperienceController::class, 'update'])->name('experiences.update');
        Route::delete('/experiences/{experience}', [ExperienceController::class, 'destroy'])->name('experiences.destroy');

        Route::post('/educations', [EducationController::class, 'store'])->name('educations.store');
        Route::patch('/educations/{education}', [EducationController::class, 'update'])->name('educations.update');
        Route::delete('/educations/{education}', [EducationController::class, 'destroy'])->name('educations.destroy');

        Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
        Route::patch('/skills/{skill}', [SkillController::class, 'update'])->name('skills.update');
        Route::delete('/skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');

        Route::post('/languages', [LanguageController::class, 'store'])->name('languages.store');
        Route::patch('/languages/{language}', [LanguageController::class, 'update'])->name('languages.update');
        Route::delete('/languages/{language}', [LanguageController::class, 'destroy'])->name('languages.destroy');

        Route::post('/certifications', [CertificationController::class, 'store'])->name('certifications.store');
        Route::patch('/certifications/{certification}', [CertificationController::class, 'update'])->name('certifications.update');
        Route::delete('/certifications/{certification}', [CertificationController::class, 'destroy'])->name('certifications.destroy');
    });

    // Placeholders: se implementarán en próximas tareas del MVP.
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
