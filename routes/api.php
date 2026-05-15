<?php

use App\Http\Controllers\KundliController;
use Illuminate\Support\Facades\Route;

Route::post('/kundli/generate', [KundliController::class, 'apiGenerate'])
    ->middleware('throttle:forms')
    ->name('api.kundli.generate');
