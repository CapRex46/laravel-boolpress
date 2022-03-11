<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/admin', 'Admin\HomeController@index')->name('admin.home')->middleware('auth');
// Route::get('/admin/products', 'Admin\ProductsController@index')->name('admin.product.index');
// Route::get('/admin/products/create', 'Admin\ProductController@index')->name('admin.product.create');
// Route::get('/admin/products/edit', 'Admin\Product@index')->name('admin.prduct.edit');
// Route::get('/admin/posts', 'Admin\PosrController@index')->name('admin.posts.index');
// Route::get('/admin/posts/create', 'Admin\PostController@index')->name('admin.posts.create');
// Route::get('/admin/posts/edit', 'Admin\PostController@index')->name('admin.posts.edit');


Route::middleware("auth")->namespace("Admin")->prefix("admin")->name("admin.")
    ->group(function (){
        Route::get('/admin', 'HomeController@index')->name('home')->middleware('auth');
        Route::get('/products', 'ProductsController@index')->name('product.index');
        Route::get('/products/create', 'ProductController@index')->name('product.create');
        Route::get('/products/edit', 'Product@index')->name('prduct.edit');
        Route::get('/posts', 'PosrController@index')->name('posts.index');
        Route::get('/posts/create', 'PostController@index')->name('posts.create');
        Route::get('/posts/edit', 'PostController@index')->name('posts.edit');

        Route::resource('comments', "CommentController");
    });


    Route::get("{any?}", function() {
        return view("admin.home");
    })->where("any", ".*");