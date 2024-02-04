<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// USER
Route::get('/settings/user', [App\Http\Controllers\UserController::class, 'index'])->name('user')->middleware('can:list_user');
Route::post('/settings/user/store', [App\Http\Controllers\UserController::class, 'store'])->name('user.store')->middleware('can:create_user');
Route::delete('/settings/user/delete/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('user.delete')->middleware('can:delete_user');
Route::put('/settings/user/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('user.update')->middleware('can:edit_user');
Route::put('/settings/user/update_password/{id}', [App\Http\Controllers\UserController::class, 'update_password'])->name('user.update_password')->middleware('can:edit_user');
