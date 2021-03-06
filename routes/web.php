<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Home\BasketController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Home\CheckoutController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Home\ProductsController as HomeProductsController;





Route::prefix('')->group(function () {

    Route::get('', [HomeProductsController::class, 'index'])->name('home.products.index');


    Route::prefix('products')
        ->group(function () {
            Route::controller(HomeProductsController::class)->group(function () {
                Route::get('single/{id}', 'single')->name('home.products.single');
                Route::get('search', 'search')->name('home.products.search');

                # Routes For Filters
                Route::get('new',  'newest')->name('home.products.filter');
                Route::get('least/to/most/price',  'lowestPrice')->name('home.products.lowestPrice');
                Route::get('most/to/least/price',  'highestPrice')->name('home.products.highestPrice');
                Route::get('ten/to/hundred/price',  'tenToHundred')->name('home.products.tenToHundred');
            });


            # Routes For Basket
            Route::controller(BasketController::class)->group(function () {
                Route::get('add-to-basket/{id}', 'addToBasket')->name('home.basket.add');
                Route::get('remove-from-basket/{id}', 'removeFromBasket')->name('home.basket.remove');
            });
            Route::controller(CheckoutController::class)->group(function(){
                Route::get('checkout', 'show')->name('home.checkout');


            });
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

Route::prefix('payments')->group(function(){
    Route::post('pay',[PaymentController::class, 'pay'])->name('payment.pay');
    Route::post('callback',[PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('callback/success', [PaymentController::class, 'callbackSuccess'])->name('callback.success');

});