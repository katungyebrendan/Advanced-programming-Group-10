<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return redirect()->route('programs.index');
});

// Facility UI (Views)
Route::view('/facility', 'Facility.index')->name('facility.index');
Route::view('/facility/create', 'Facility.create')->name('facility.create');
Route::view('/facility/{id}', 'Facility.show')->name('facility.show');
Route::view('/facility/{id}/edit', 'Facility.edit')->name('facility.edit');

//Program UI Views 
Route::resource('programs', ProgramController::class);


//Projects UI Views
Route::get('/projects/view', [ProjectController::class, 'listView'])->name('projects.index');
Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::get('/projects/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
Route::get('/projects/{id}', [ProjectController::class, 'showView'])->name('projects.show');