<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EquipmentController;

Route::get('/', function () {
    return redirect()->route('programs.index');
});

// Facilities UI Views
Route::resource('facilities', FacilityController::class);

// Program UI Views
Route::resource('programs', ProgramController::class);

<<<<<<< HEAD
// Projects UI Views
=======
//Equipment UI views
Route::resource('equipment', EquipmentController::class);



//Projects UI Views
>>>>>>> 7b3349d (Added equipment CRUD functionality)
Route::get('/projects/view', [ProjectController::class, 'listView'])->name('projects.view');