<?php

use App\Http\Controllers\Api\CvController as ApiCvController;
use App\Http\Controllers\Cv\CvController;
use App\Http\Controllers\JobOffer\JobOfferController;
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

    Route::prefix('cvs')->name('cvs.')->group(function () {
        Route::get('/', [CvController::class, 'index'])->name('index');
        Route::get('/{cv}', [CvController::class, 'show'])->name('show');
    });

    Route::prefix('api/cvs')->name('api.cvs.')->group(function () {
        Route::post('/', [ApiCvController::class, 'store'])->name('store');
        Route::post('/{cv}/analyze', [ApiCvController::class, 'analyze'])->name('analyze');
    });

    Route::prefix('job-offers')->name('job-offers.')->group(function () {
        Route::get('/', [JobOfferController::class, 'index'])->name('index');
        Route::get('/create', [JobOfferController::class, 'create'])->name('create');
        Route::post('/', [JobOfferController::class, 'store'])->name('store');
        Route::get('/{jobOffer}', [JobOfferController::class, 'show'])->name('show');
        Route::get('/{jobOffer}/edit', [JobOfferController::class, 'edit'])->name('edit');
        Route::patch('/{jobOffer}', [JobOfferController::class, 'update'])->name('update');
        Route::delete('/{jobOffer}', [JobOfferController::class, 'destroy'])->name('destroy');
    });

    // Placeholders: se implementarán en próximas tareas del MVP.
    Route::get('/cover-letters', function () {
        return view('coming-soon', ['title' => 'Cartas de presentación']);
    })->name('cover-letters.index');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings', [SettingsController::class, 'destroy'])->name('settings.destroy');
});

require __DIR__.'/auth.php';
