<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // EVENTS ROUTES
    Route::match(['get','post'],'events',[EventController::class,'index'])->name('events');
    Route::match(['get','post'],'add_event',[EventController::class,'addEvent'])->name('add_event');
    Route::match(['get','post'],'update_event',[EventController::class,'updateEvent'])->name('update_event');
    Route::get('/get_user_by_email', [UserController::class, 'getUserByEmail'])->name('get_user_by_email');
    Route::post('delete_event', [EventController::class, 'deleteEvent'])->name('delete_event');

});

require __DIR__.'/auth.php';
