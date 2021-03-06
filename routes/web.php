<?php

use App\Http\Controllers\GraphController;
use App\Http\Controllers\MapController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'index')->name('home');

Route::view('/test1', 'test1')->name('test1');

Route::view('/test2', 'test2')->name('test2');

Route::get('/graph', [GraphController::class, 'show'])->name('graph');

Route::get('/map', [MapController::class, 'show'])->name('map');
Route::get('/map/{id}', [MapController::class, 'showLink'])->name('map-link');
