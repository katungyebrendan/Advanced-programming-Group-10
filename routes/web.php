<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\OutcomeController;

Route::get('/', function () {
    return redirect()->route('programs.index');
});

// Resource routes
Route::resource('facilities', FacilityController::class);
Route::resource('programs', ProgramController::class);
Route::resource('equipment', EquipmentController::class);
Route::resource('services', ServiceController::class);
Route::resource('participants', ParticipantController::class);

// Projects routes
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');

Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::get('/projects/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
Route::put('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');

// Nested routes for project-specific outcomes
Route::prefix('projects/{project}')->group(function () {
    Route::get('outcomes', [OutcomeController::class, 'index'])->name('projects.outcomes.index');
    Route::get('outcomes/create', [OutcomeController::class, 'create'])->name('projects.outcomes.create');
    Route::post('outcomes', [OutcomeController::class, 'store'])->name('projects.outcomes.store');
});

// Standalone outcome routes (show, edit, update, destroy)
Route::get('outcomes/{outcome}', [OutcomeController::class, 'show'])->name('outcomes.show');
Route::get('outcomes/{outcome}/edit', [OutcomeController::class, 'edit'])->name('outcomes.edit');
Route::put('outcomes/{outcome}', [OutcomeController::class, 'update'])->name('outcomes.update');
Route::delete('outcomes/{outcome}', [OutcomeController::class, 'destroy'])->name('outcomes.destroy');

// Custom routes
Route::get('facilities/{facility}/services', [ServiceController::class, 'byFacility'])->name('facilities.services');
Route::get('participants/{participant}/projects', [ParticipantController::class, 'projects'])->name('participants.projects');
// routes participants - projects
Route::resource('participants', ParticipantController::class);
Route::get('participants/{participant}/projects', [ParticipantController::class, 'manageProjects'])
    ->name('participants.manage-projects');
Route::put('participants/{participant}/projects', [ParticipantController::class, 'updateProjects'])
    ->name('participants.update-projects');