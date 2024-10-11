<?php

use App\Http\Controllers\ImageController;
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
    // Multiple images uploaded
    Route::get('images-uploads',[ImageController::class,'index']);
    Route::get('/create',[ImageController::class,'create']);
    Route::post('/post',[ImageController::class,'store']);
    Route::delete('/delete/{id}',[ImageController::class,'destroy']);
    Route::get('/edit/{id}',[ImageController::class,'edit']);
    Route::delete('/deleteimage/{id}',[ImageController::class,'deleteimage']);
    Route::delete('/deletecover/{id}',[ImageController::class,'deletecover']);
    Route::put('/update/{id}',[ImageController::class,'update']);

});



require __DIR__.'/auth.php';
