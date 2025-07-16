<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AxeController;
use App\Http\Controllers\SousAxeController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\CoordinateurController;
use App\Http\Controllers\ResponsableController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Pest\Plugins\Only;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']) // redirige selon le rôle
        ->name('dashboard');
});


// Routes accessibles uniquement aux administrateurs
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('dashboard.admin');
    Route::resource('/axe', AxeController::class);
    Route::resource('/sousaxe', SousAxeController::class);
    Route::resource('/action', ActionController::class);
    Route::resource('/user', UserController::class);
});

// Coordinateur
Route::middleware(['auth'])->group(function () {
    Route::get('/coordinateur', [CoordinateurController::class, 'index'])->name('dashboard.coordinateur');
    // Routes spécifiques à ce rôle
    Route::get('/mes-axes-coordinateur', [AxeController::class, 'mesAxes'])->name('coordinateur.axe.mesAxes');
    Route::get('/mes-sous-axes-coordinateur', [SousAxeController::class, 'mesSousAxes'])->name('coordinateur.sousaxe.mesSousAxes');
    Route::get('/mes-actions-coordinateur', [ActionController::class, 'mesActions'])->name('coordinateur.action.mesActions');
});

// Responsable d'action
Route::middleware(['auth'])->group(function () {
    Route::get('/responsable', [ResponsableController::class, 'index'])->name('dashboard.responsable');
    // Routes spécifiques à ce rôle
    Route::get('/mes-axe-responsable', [AxeController::class, 'mesAxes'])->name('responsable.axe.mesAxes');
    Route::get('/mes-sous-axe-responsable', [SousAxeController::class, 'mesSousAxes'])->name('responsable.sousaxe.mesSousAxes');
    Route::get('/mes-action-responsable', [ActionController::class, 'mesActions'])->name('responsable.action.mesActions');
});


// Consultant
Route::middleware(['auth'])->group(function () {
    Route::get('/consultant', [ConsultantController::class, 'index'])->name('dashboard.consultant');
    // Routes spécifiques à ce rôle
    Route::get('/mes-axe-consultant', [AxeController::class, 'mesAxes'])->name('consultant.axe');
    Route::get('/mes-sous-axe-consultant', [SousAxeController::class, 'mesSousAxes'])->name('consultant.sousaxe');
    Route::get('/mes-action-consultant', [ActionController::class, 'mesActions'])->name('consultant.action');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
