<?php

use App\Http\Controllers\Admin\CategoriesController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->group(function (){
    Route::prefix('categories')->group(function (){
        // routes-For-Admin-Categories
        Route::get('', [CategoriesController::class, 'all'])->name('admin.categories.all');
        Route::get('add', [CategoriesController::class, 'create'])->name('admin.categories.add');
        Route::post('', [CategoriesController::class, 'store'])->name('admin.categories.stor');
        Route::delete('category/{id}', [CategoriesController::class, 'delete'])->name('admin.categories.delete');
        Route::get('edit/{id}', [CategoriesController::class, 'edit'])->name('admin.categories.edit');
        Route::put('update/{id}', [CategoriesController::class, 'update'])->name('admin.categories.update');
    });
});