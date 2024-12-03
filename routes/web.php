<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\NoteController;

Route::get('/', function () {
    return view('website.home');
});

Route::prefix('/home')->group(function () {
    Route::get('/', [NoteController::class, 'home'])->name('home');
    Route::get('/{slug}', [NoteController::class, 'read'])->name('read.note');
    Route::get('/buat-catatan-baru', [NoteController::class, 'create'])->name('create.note');
    Route::post('/', [NoteController::class, 'store'])->name('put.note');
    Route::get('/edit-catatan/{slug}', [NoteController::class, 'edit'])->name('edit.note');
    Route::put('/{slug}', [NoteController::class, 'update'])->name('update.note');
    Route::delete('/{slug}', [NoteController::class, 'delete'])->name('delete.note');
});