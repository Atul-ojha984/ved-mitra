<?php

use App\Http\Controllers\KundliController;
use App\Http\Controllers\DiagnosticController;
use Illuminate\Support\Facades\Route;

Route::get('/diagnostic/admin', [DiagnosticController::class, 'checkAdmin'])
    ->middleware('throttle:10,1')
    ->name('api.diagnostic.admin');

Route::post('/kundli/generate', [KundliController::class, 'apiGenerate'])
    ->middleware('throttle:forms')
    ->name('api.kundli.generate');
