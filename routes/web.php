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

// Facilities UI Views
Route::resource('facilities', FacilityController::class);

// Program UI Views
Route::resource('programs', ProgramController::class);

// Equipment UI Views
Route::resource('equipment', EquipmentController::class);

// Projects UI Views
Route::get('/projects/view', [ProjectController::class, 'index'])->name('projects.view');
Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
Route::get('/projects/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
Route::resource('services', ServiceController::class);
// List services by facility
Route::get('facilities/{facility}/services', [ServiceController::class, 'byFacility'])->name('facilities.services');


// Participant CRUD
Route::resource('participants', ParticipantController::class);



// List projects for a participant
Route::get('participants/{participant}/projects', [ParticipantController::class, 'projects'])->name('participants.projects');


// Global Outcome CRUD
Route::get('projects/{project}/outcomes', [OutcomeController::class, 'index'])->name('projects.outcomes.index');
Route::get('projects/{project}/outcomes/create', [OutcomeController::class, 'create'])->name('projects.outcomes.create');
Route::post('projects/{project}/outcomes', [OutcomeController::class, 'store'])->name('projects.outcomes.store');
Route::get('outcomes/{outcome}', [OutcomeController::class, 'show'])->name('outcomes.show');
Route::get('outcomes/{outcome}/edit', [OutcomeController::class, 'edit'])->name('outcomes.edit');
Route::put('outcomes/{outcome}', [OutcomeController::class, 'update'])->name('outcomes.update');
Route::delete('outcomes/{outcome}', [OutcomeController::class, 'destroy'])->name('outcomes.destroy');

//Projects UI Views
Route::get('/projects/view', [ProjectController::class, 'listView'])->name('projects.view');
Route::resource('projects', ProjectController::class);

//Outcome CRUD
Route::resource('outcomes', OutcomeController::class);

