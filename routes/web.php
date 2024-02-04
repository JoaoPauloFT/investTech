<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// SECTOR
Route::get('/settings/sector', [App\Http\Controllers\SectorController::class, 'index'])->name('sector')->middleware('can:list_sector');
Route::get('/settings/sector/sync', [App\Http\Controllers\SectorController::class, 'sync'])->name('sector.sync')->middleware('can:sync_sector');

// USER
Route::get('/settings/user', [App\Http\Controllers\UserController::class, 'index'])->name('user')->middleware('can:list_user');
Route::post('/settings/user/store', [App\Http\Controllers\UserController::class, 'store'])->name('user.store')->middleware('can:create_user');
Route::delete('/settings/user/delete/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('user.delete')->middleware('can:delete_user');
Route::put('/settings/user/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('user.update')->middleware('can:edit_user');
Route::put('/settings/user/update_password/{id}', [App\Http\Controllers\UserController::class, 'update_password'])->name('user.update_password')->middleware('can:edit_user');

// OTHER
Route::post('/file-upload', [App\Http\Controllers\FileUploadController::class, 'FileUpload' ])->name('FileUpload');
