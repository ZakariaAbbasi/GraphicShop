<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\HomeController;

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Home\ProductsController as HomeProductsController;

Route::prefix('')->group(function () {

    Route::get('', [HomeProductsController::class, 'index'])->name('home.products.index');


    Route::prefix('products')
        ->controller(HomeProductsController::class)->group(function () {

            Route::get('single/{id}', 'single')->name('home.products.single');
            Route::get('search', 'search')->name('home.products.search');
            Route::get('new',  'filter')->name('home.products.filter');
        });
});


Route::get('panel', function () {
    return view('admin.index');
})->name('panel.index');

Route::prefix('admin')->group(function () {



    // routes-For-Admin-Categories
    Route::prefix('categories')
        ->controller(CategoriesController::class)
        ->group(function () {

            Route::get('', 'all')->name('admin.categories.all');
            Route::get('add', 'create')->name('admin.categories.add');
            Route::post('', 'store')->name('admin.categories.stor');
            Route::delete('category/{id}', 'delete')->name('admin.categories.delete');
            Route::get('edit/{id}', 'edit')->name('admin.categories.edit');
            Route::put('update/{id}', 'update')->name('admin.categories.update');
        });


    Route::prefix('products')
        ->controller(ProductsController::class)->group(function () {

            Route::get('add', 'create')->name('admin.products.add');
            Route::post('', 'store')->name('admin.products.store');
            Route::get('', 'all')->name('admin.products.all');
            Route::delete('product/{id}', 'delete')->name('admin.products.delete');
            Route::get('edit/{id}', 'edit')->name('admin.products.edit');
            Route::put('update/{id}', 'update')->name('admin.products.update');

            Route::get('download/{id}/demo', 'downloadDemo')->name('admin.products.download.demo');
            Route::get('download/{id}/source', 'downloadSource')->name('admin.products.download.source');
        });

    Route::prefix('users')
        ->controller(UsersController::class)->group(function () {

            Route::get('add', 'create')->name('admin.users.add');
            Route::get('all', 'all')->name('admin.users.all');
            Route::post('', 'store')->name('admin.users.store');
            Route::delete('delete/{id}', 'delete')->name('admin.users.delete');
            Route::get('edit/{id}', 'edit')->name('admin.users.edit');
            Route::put('update/{id}', 'update')->name('admin.users.update');
        });

    Route::prefix('orders')
        ->controller(OrdersController::class)->group(function () {
            Route::get('all', 'all')->name('admin.orders.all');
        });

    Route::prefix('payments')
        ->controller(PaymentsController::class)->group(function () {
            Route::get('all', 'all')->name('admin.payments.all');
        });
});
