<?php

use App\Http\Controllers\ProfileController;
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

require __DIR__.'/auth.php';

//Users
Route::get('/', 'App\Http\Controllers\UserController@index')->middleware(['auth', 'verified'])->name('users');
Route::post('/user', 'App\Http\Controllers\UserController@store')->middleware(['auth', 'verified'])->name('user.store');
Route::put('/user/{id}', 'App\Http\Controllers\UserController@update')->middleware(['auth', 'verified'])->name('user.update');
Route::delete('/user/{id}', 'App\Http\Controllers\UserController@destroy')->middleware(['auth', 'verified'])->name('user.destroy');
Route::get('/user/{id}', 'App\Http\Controllers\UserController@get')->middleware(['auth', 'verified'])->name('user.get');
Route::get('/users/search/all', 'App\Http\Controllers\UserController@search')->middleware(['auth', 'verified'])->name('users.search');



