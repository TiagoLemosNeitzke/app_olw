<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

Route::group([
    'prefix' => 'beers',
    'middleware' => 'auth'
], function () {
    Route::get('/', [App\Http\Controllers\BeerController::class, 'index'])->name('beers');

    Route::post('/export', [App\Http\Controllers\BeerController::class, 'export'])->name('beers.export');

    Route::resource('reports', App\Http\Controllers\ExportController::class)->only('index', 'show', 'destroy');
});
