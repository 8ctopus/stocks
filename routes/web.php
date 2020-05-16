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

// views
Route::get('/user/{user}', 'UserController@trades')->name('user.trades');
Route::get('/user/{user}/currency/{currency}', 'UserController@tradesByCurrency');
Route::get('/user/{user}/stock/{stock}', 'UserController@stock');

Route::get('/stock/{stock}', 'StockController@index');
