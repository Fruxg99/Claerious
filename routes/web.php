<?php

use App\Http\Controllers\Backoffice\dashboard;
use App\Http\Controllers\Backoffice\item;
use App\Http\Controllers\Backoffice\mainBackoffice;
use App\Http\Controllers\Backoffice\report;
use App\Http\Controllers\Backoffice\storefront;
use App\Http\Controllers\Backoffice\storeSetting;
use App\Http\Controllers\Backoffice\unit;
use App\Http\Controllers\Backoffice\variant;
use App\Http\Controllers\Store\cart;
use App\Http\Controllers\Store\checkout;
use App\Http\Controllers\Store\main;
use App\Http\Controllers\Store\home;
use App\Http\Controllers\Store\product;
use App\Http\Controllers\Store\wishlist;
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

Route::domain('backoffice.claerious.com')->group(function () {
    Route::get('/', function () {
        return view('Backoffice.dashboard');
    });

    // Dashboard Controller
    Route::controller(dashboard::class)->group(function () {
        Route::get('/dashboard', 'load');
    });

    // Item Controller
    Route::controller(item::class)->group(function () {
        Route::get('/product', 'load');
    });

    // Main Backoffice Controller
    Route::controller(mainBackoffice::class)->group(function () {
        Route::get('/login', 'load');
    });

    // Report Controller
    Route::controller(report::class)->group(function () {
        Route::get('/report', 'load');
    });

    // Storefront Controller
    Route::controller(storefront::class)->group(function () {
        Route::get('/storefront', 'load');
    });

    // Store Setting Controller
    Route::controller(storeSetting::class)->group(function () {
        Route::get('/setting', 'load');
    });

    // Unit Controller
    Route::controller(unit::class)->group(function () {
        Route::get('/unit', 'load');
    });

    // Variant Controller
    Route::controller(variant::class)->group(function () {
        Route::get('/variant', 'load');
    });
});

Route::domain('www.claerious.com')->group(function () {
    Route::get('/', function () {
        return view('Template.store');
    });

    // Cart Controller
    Route::controller(cart::class)->group(function () {
        Route::get('/cart', 'load');
    });

    // Checkout Controller
    Route::controller(checkout::class)->group(function () {
        Route::get('/checkout', 'load');
    });

    // Home Controller
    Route::controller(home::class)->group(function () {
        Route::get('/home', 'load');
    });

    // Main Controller
    Route::controller(main::class)->group(function () {
        Route::get('/', 'load');
    });

    // Product Controller
    Route::controller(product::class)->group(function () {
        Route::get('/product', 'load');
    });

    // Wishlist Controller
    Route::controller(wishlis::class)->group(function () {
        Route::get('/wishlist', 'load');
    });
});
