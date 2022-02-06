<?php

use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ProductsController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;



Route::prefix('admin')->group(function (){
    Route::prefix('products')
    ->controller(ProductsController::class)->group(function (){

        Route::get('add', 'create')->name('admin.products.add');
        Route::post('', 'store')->name('admin.products.store');
        Route::get('', 'all')->name('admin.products.all');
        Route::get('', 'all')->name('admin.products.all');


    });
 // routes-For-Admin-Categories
    Route::prefix('categories')
    ->controller(CategoriesController::class)
    ->group(function (){
       
        Route::get('', 'all')->name('admin.categories.all');
        Route::get('add', 'create')->name('admin.categories.add');
        Route::post('', 'store')->name('admin.categories.stor');
        Route::delete('category/{id}', 'delete')->name('admin.categories.delete');
        Route::get('edit/{id}', 'edit')->name('admin.categories.edit');
        Route::put('update/{id}', 'update')->name('admin.categories.update');

    });
});

