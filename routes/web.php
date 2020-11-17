<?php

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

Route::group(['middleware' => ['auth']], function () {
    // 一般ユーザー用のルーティング
    Route::get('/', 'ProductsController@index')->name('products.index');
    Route::get('search', "ProductsController@search")->name('products.search');

    Route::group(['prefix' => 'products'], function () {
        Route::get('show/{id}', 'ProductsController@show')->name('products.show');
        Route::get('likes', 'ProductsController@likeProducts')->name('products.likes');
        Route::post('addLike/{id}', 'ProductsController@addLike')->name('products.addLike');

        Route::get('cart', 'ProductsController@cart')->name('products.cart');
        Route::post('addToCart/{id}', 'ProductsController@addToCart')->name('AddToCart');
        Route::post('deleteItemFromCart/{id}', 'ProductsController@deleteItemFromCart')->name('DeleteItemFromCart');

        Route::post('increaseSingleProduct/{id}', 'ProductsController@increaseSingleProduct')->name('IncreaseSingleProduct');
        Route::post('decreaseSingleProduct/{id}', 'ProductsController@decreaseSingleProduct')->name('DecreaseSingleProduct');
    });

    Route::group(['prefix' => 'payments'], function () {
        Route::post('sample_pay', 'PaymentController@sample_pay')->name('sample_pay');
        Route::get('paypal_approval', 'PaymentController@paypal_approval')->name('paypal_approval');
        Route::get('paypal_cancelled', 'PaymentController@cancelled')->name('paypal_cancelled');
    });
});



// ログアウト
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

// 管理者用のルーティング
Route::group(['middleware' => ['auth', 'can:admin-only']], function () {
    Route::group(['prefix' => 'admin/products'], function () {
        Route::get('index', 'AdminController@allproducts')->name('admin.products.index');
        Route::get('create', 'AdminController@create')->name('admin.products.create');
        Route::delete('delete/{id}', 'AdminController@delete')->name('admin.products.delete');
        Route::post('store', 'AdminController@store')->name('admin.products.store');
        Route::get('edit/{id}', 'AdminController@edit')->name('admin.products.edit');
        Route::post('update/{id}', 'AdminController@update')->name('admin.products.update');

        Route::get('editImage/{id}', 'AdminController@editImage')->name('admin.products.editImage');
        Route::post('updateImage/{id}', 'AdminController@updateImage')->name('admin.products.updateImage');
    });

    Route::group(['prefix' => 'order'], function () {
        Route::get('panel', 'AdminController@order_panel')->name('order_panel');
        Route::post('status', 'AdminController@order_status')->name('order_status');
    });
});

// 認証機能用のルーティング
Auth::routes();
