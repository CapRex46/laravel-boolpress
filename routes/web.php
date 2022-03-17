<?php

use Illuminate\Support\Facades\Auth;
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

// Route::get('/', "HomeController@index");

Auth::routes();
/*
Route::get('/admin', 'Admin\HomeController@index')->name('admin.home')->middleware('auth');
Route::get('/admin/products', 'Admin\ProductController@index')->name('admin.product.inde')->middleware('auth');
Route::get('/admin/products/create', 'Admin\ProductController@index')->name('admin.product.create')->middleware('auth');
Route::get('/admin/products/edit', 'Admin\ProductController@index')->name('admin.product.edit')->middleware('auth');
Route::get('/admin/posts/', 'Admin\PostController@index')->name('admin.post.index')->middleware('auth');
Route::get('/admin/posts/create', 'Admin\PostController@index')->name('admin.post.create')->middleware('auth');
Route::get('/admin/posts/edit', 'Admin\PostController@index')->name('admin.post.edit')->middleware('auth');
 */
Route::middleware("auth")
  ->namespace("Admin")
  ->prefix("admin")
  ->name("admin.")
  ->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Route::get('/products', 'ProductController@index')->name('product.index');
    // Route::get('/products/create', 'ProductController@index')->name('product.create');
    // Route::get('/products/edit', 'ProductController@index')->name('product.edit');

    Route::resource("posts", "PostController");
    Route::resource("comments", "CommentController");
    Route::resource("users", "UserController");

    // Route::get("users", "UserController@index")->name("users.index");
  });

// Route::get("/comments", "CommentController@index")->name("comments");

Route::get("{any?}", function () {
  return view("home");
})->where("any", ".*");