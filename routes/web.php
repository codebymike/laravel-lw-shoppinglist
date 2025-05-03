<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShoppingListController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/list/{shoppingList}', [ShoppingListController::class, 'show'])
    ->middleware(['auth'])
    ->name('list.show');

require __DIR__.'/auth.php';
