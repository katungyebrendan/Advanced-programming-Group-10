<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Facility UI (Views)
Route::view('/facility', 'Facility.index')->name('facility.index');
Route::view('/facility/create', 'Facility.create')->name('facility.create');
Route::view('/facility/{id}', 'Facility.show')->name('facility.show');
Route::view('/facility/{id}/edit', 'Facility.edit')->name('facility.edit');