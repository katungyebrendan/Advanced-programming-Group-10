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
<<<<<<< HEAD
Route::get('/projects/view', [ProjectController::class, 'listView'])->name('projects.index');
Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::get('/projects/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
Route::get('/projects/{id}', [ProjectController::class, 'showView'])->name('projects.show');
=======
>>>>>>> 7b3349d (Added equipment CRUD functionality)
Route::get('/projects/view', [ProjectController::class, 'listView'])->name('projects.view');
>>>>>>> 6594614d63d86d57a1259b2d790d307540470780
