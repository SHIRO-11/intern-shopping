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

    Route::get('products/show/{id}', 'ProductsController@show')->name('products.show');
    Route::get('/products/likes', 'ProductsController@likeProducts')->name('products.likes');
    Route::get('/products/addLike/{id}', 'ProductsController@addLike')->name('products.addLike');

    Route::get('/products/cart', 'ProductsController@cart')->name('products.cart');
    Route::get('product/addToCart/{id}', 'ProductsController@addToCart')->name('AddToCart');
    Route::get('product/deleteItemFromCart/{id}', 'ProductsController@deleteItemFromCart')->name('DeleteItemFromCart');

    Route::get('product/increaseSingleProduct/{id}', ['uses'=>'ProductsController@increaseSingleProduct','as'=>'IncreaseSingleProduct']);
    Route::get('product/decreaseSingleProduct/{id}', ['uses'=>'ProductsController@decreaseSingleProduct','as'=>'DecreaseSingleProduct']);

    Route::post('/payments/sample_pay', 'PaymentController@sample_pay')->name('sample_pay');
    Route::get('/payments/paypal_approval', 'PaymentController@paypal_approval')->name('paypal_approval');
    Route::get('/payments/paypal_cancelled', 'PaymentController@cancelled')->name('paypal_cancelled');
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


    Route::get('/order_panel', 'AdminController@order_panel')->name('order_panel');
    Route::get('/order_status', 'AdminController@order_status')->name('order_status');
});

// 認証機能用のルーティング
Auth::routes();
