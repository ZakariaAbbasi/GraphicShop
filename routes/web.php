<?php

use App\Http\Controllers\Admin\CategoriesController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->group(function (){
    Route::prefix('categories')->group(function (){
        // routes-For-Admin-Categories
        Route::get('', [CategoriesController::class, 'all'])->name('admin.categories.all');
        Route::get('add', [CategoriesController::class, 'create'])->name('admin.categories.add');
        Route::post('', [CategoriesController::class, 'store'])->name('admin.categories.stor');

    });
});