<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\FacilityController; 
Route::get('/', function () {
    return redirect()->route('programs.index');
});

// Facilities UI Views
Route::resource('facilities', FacilityController::class);

// Program UI Views
Route::resource('programs', ProgramController::class);

// Projects UI Views
Route::get('/projects/view', [ProjectController::class, 'listView'])->name('projects.view');