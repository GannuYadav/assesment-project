<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::match(['get', 'post'],'/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/bucket-save', [App\Http\Controllers\HomeController::class, 'BucketForm'])->name('bucket-save');
Route::post('/ball-type-save', [App\Http\Controllers\HomeController::class, 'BallTypeForm'])->name('ball-type-save');
Route::post('/ball-save', [App\Http\Controllers\HomeController::class, 'BallForm'])->name('ball-save');
