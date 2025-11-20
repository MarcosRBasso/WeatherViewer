<?php

use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WeatherController::class, 'index'])->name('weather.index');

Route::post('/cep', [WeatherController::class, 'fillCityByCep'])->name('weather.fillCity');
Route::post('/search', [WeatherController::class, 'search'])->name('weather.search');
Route::post('/save-today', [WeatherController::class, 'saveToday'])->name('weather.saveToday');

Route::get('/history', [WeatherController::class, 'history'])->name('weather.history');
Route::post('/compare', [WeatherController::class, 'compare'])->name('weather.compare');
