<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard/stats', [ClientController::class, 'stats']);

Route::apiResource('clients', ClientController::class);
Route::apiResource('contacts', ContactController::class)->except(['create', 'edit']);
Route::apiResource('notes', NoteController::class)->except(['create', 'edit']);
Route::apiResource('tasks', TaskController::class)->except(['create', 'edit']);
