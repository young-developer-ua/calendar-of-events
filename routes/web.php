<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::group(['prefix' => 'events', 'as' => 'events.'], function ()
    {
        Route::get('/', [EventController::class, 'index'])->name('get');
        Route::post('update', [EventController::class, 'update'])->name('update');
        Route::post('store', [EventController::class, 'store'])->name('store');
        Route::delete('/{event}/delete', [EventController::class, 'delete'])->name('delete');
        Route::post('/{event}/switch-done', [EventController::class, 'markSwitch'])->name('done');
    });
});

require __DIR__.'/auth.php';
