<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\FavoriteController;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Questions CRUD
    Route::resource('questions', QuestionController::class);

    // Responses
    Route::post('questions/{question}/responses', [ResponseController::class, 'store'])->name('responses.store');

    // Favorites
    Route::post('questions/{question}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});
