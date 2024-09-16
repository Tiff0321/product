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

//Route::get('/', function () { return view('welcome');});
Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');
//用户功能开发
Route::resource('users','UsersController');

//Route::get('/users', 'UsersController@index')->name('users.index');
//Route::get('/users/create', 'UsersController@create')->name('users.create');
//Route::get('/users/{user}', 'UsersController@show')->name('users.show');
//Route::post('/users', 'UsersController@store')->name('users.store');
//Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
//Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
//Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');
//会话的创建与销毁
Route::get('login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');

//邮箱认证
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');

//忘记密码
Route::get('password/reset',  'PasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email',  'PasswordController@sendResetLinkEmail')->name('password.email');

Route::get('password/reset/{token}',  'PasswordController@showResetForm')->name('password.reset');
Route::post('password/reset',  'PasswordController@reset')->name('password.update');
//产品功能开发
Route::resource('products','ProductsController');

//Route::get('/products', 'ProductsController@index')->name('products.index');
//Route::get('/products/create', 'ProductsController@create')->name('products.create');
//Route::get('/products/{product}', 'ProductsController@show')->name('products.show');
//Route::post('/products', 'ProductsController@store')->name('products.store');
//Route::get('/products/{product}/edit', 'ProductsController@edit')->name('products.edit');
//Route::patch('/products/{product}', 'ProductsController@update')->name('products.update');
//Route::delete('/products/{product}', 'ProductsController@destroy')->name('products.destroy');

Route::get('/users/{user}/favorite','UsersController@favorite')->name('users.favorite');
Route::get('/users/{user}/purchased','UsersController@purchased')->name('users.purchased');

Route::post('/products/{product}/favorite','ProductsController@favorite')->name('products.favorite');
Route::delete('/products/{product}/favorite','ProductsController@unfavorite')->name('products.unfavorite');
Route::post('/products/{product}/purchased','ProductsController@purchased')->name('products.purchased');

Route::get('/products/{product}/images','ProductImagesController@edit')->name('ProductImages.edit');
Route::post('/products/{product}/images','ProductImagesController@update')->name('ProductImages.update');
Route::delete('/products/{product}/images/{image}','ProductImagesController@delete')->name('ProductImages.delete');
////收藏和取消收藏
//Route::post('/users/favorite/{user}', 'UsersController@favorite')->name('followers.store');
//Route::delete('/users/followers/{user}', 'FollowersController@destroy')->name('followers.destroy');
