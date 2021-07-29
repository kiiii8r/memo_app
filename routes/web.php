<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

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

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/complete', [UserController::class, 'complete'])->name('complete');
Route::post('/store', [HomeController::class, 'store'])->name('store');
Route::get('/content/{id}', [HomeController::class, 'content'])->name('content');
Route::get('/edit/{id}', [HomeController::class, 'edit'])->name('edit');
Route::post('/update', [HomeController::class, 'update'])->name('update');
Route::post('/destroy', [HomeController::class, 'destroy'])->name('destroy');
Route::post('/tag_destroy', [HomeController::class, 'tag_destroy'])->name('tag_destroy');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/read/{id}', [HomeController::class, 'read'])->name('read');
Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');
Route::get('/user/{id}', [UserController::class, 'user'])->name('user');
Route::post('/user_update', [UserController::class, 'user_update'])->name('user_update');
Route::post('/image_up', [UserController::class, 'image_up'])->name('image_up');