<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GedController;

use Illuminate\Support\Facades\Route;

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

    //ROTAS CRIADAS
    Route::get('/ged/{tipo}/{path?}', [GedController::class, 'explorar'])->where('path', '.*');
    Route::get('/ged-arquivo/{tipo}/{path}', [GedController::class, 'arquivo'])->where('path', '.*');
    Route::get('/ged-download/{tipo}/{path}', [GedController::class, 'download'])->where('path', '.*');
    Route::post('/ged/{tipo}/upload', [GedController::class, 'upload']);
    Route::delete('/ged/{tipo}/delete', [GedController::class, 'delete']);
    Route::post('/ged/{tipo}/rename', [GedController::class, 'rename']);
    Route::post('/ged/{tipo}/folder', [GedController::class, 'createFolder']);
    Route::delete('/ged/{tipo}/delete-multiple', [GedController::class, 'deleteMultiple']
);
    });

require __DIR__.'/auth.php';
