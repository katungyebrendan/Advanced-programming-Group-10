<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgramController;

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