<?php

use App\Http\Controllers\Backoffice\dashboard;
use App\Http\Controllers\Backoffice\item;
use App\Http\Controllers\Backoffice\mainBackoffice;
use App\Http\Controllers\Backoffice\report;
use App\Http\Controllers\Backoffice\storefront;
use App\Http\Controllers\Backoffice\transaction;
use App\Http\Controllers\Backoffice\variant;
use App\Http\Controllers\Backoffice\voucher;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Store\cart;
use App\Http\Controllers\Store\checkout;
use App\Http\Controllers\Store\favorite;
use App\Http\Controllers\Store\main;
use App\Http\Controllers\Store\home;
use App\Http\Controllers\Store\product;
use App\Http\Controllers\Store\wishlist;
use Doctrine\DBAL\Driver\Middleware;
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

Route::domain('backoffice.claerious.store')->group(function () {
    // Google Auth
    Route::get('/session/seller', [GoogleController::class, 'sessionGoogle']);
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

    // Dashboard Controller
    Route::controller(dashboard::class)->group(function () {
        Route::get('/dashboard', 'load');
    });

    // Item Controller
    Route::controller(item::class)->group(function () {
        Route::any('/product', 'load');
        Route::post('/product/{mode}', 'crud');
    });

    // Transaction Controller
    Route::controller(transaction::class)->group(function () {
        Route::any('/transaction', 'load');
        Route::post('/transaction/{mode}', 'crud');
    });

    // Main Backoffice Controller
    Route::controller(mainBackoffice::class)->group(function () {
        Route::get('/', 'load')->middleware("checkLoginSeller");
        Route::post('/login', 'login');
        Route::get('/logout', 'logout');
        Route::get('/category', 'loadCategory');
        Route::post('/category/{mode}', 'crudCategory');

        Route::get('/report/sales', 'loadSalesReport');
        Route::post('/report/sales/{mode}', 'salesReportCRUD');
        
        Route::get('/report/stock', 'loadStockReport');
        Route::post('/report/stock/{mode}', 'stockReportCRUD');

        Route::get('/report/income', 'loadIncomeReport');
        Route::post('/report/income/{mode}', 'incomeReportCRUD');

        Route::get('/report/seller', 'loadSellerReport');
        Route::post('/report/seller/{mode}', 'sellerReportCRUD');

        Route::get('/report/voucher', 'loadVoucherReport');
        Route::post('/report/voucher/{mode}', 'voucherReportCRUD');

        Route::get('/report/user', 'loadUserReport');
        Route::post('/report/user/{mode}', 'userReportCRUD');
    });

    // Report Controller
    Route::controller(report::class)->group(function () {
        Route::get('/report', 'load');
    });

    // Voucher Controller
    Route::controller(voucher::class)->group(function () {
        Route::get('/voucher', 'load');
        Route::post('/voucher/{mode}', 'crud');
    });
});

// Route::domain('www.claerious.store')->group(function () {
    Route::get('/store', function () {
        return view('Template.store');
    });

    Route::get('/register', [main::class, 'loadRegister']);
    Route::post('/login', [main::class, 'login']);
    Route::get('/logout', [main::class, 'logout']);

    // Google Auth
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

    // Cart Controller
    Route::controller(cart::class)->group(function () {
        Route::get('/cart', 'load');
        Route::post('/cart/get-cart', 'getCart');
        Route::post('/cart/add-to-cart', 'addToCart');
        Route::post('/cart/update-cart-qty', 'updateCartQty');
        Route::post('/cart/remove-cart-item', 'removeCartItem');
        Route::post('/cart/cart-count', 'cartCount');
    });

    // Checkout Controller
    Route::controller(checkout::class)->group(function () {
        Route::post('/checkout', 'checkout');
        Route::post('/checkout/payment-success', 'paymentSuccess');
        Route::post('/checkout/payment-failed', 'paymentFailed');

        Route::post('/group/checkout', 'checkoutGroup');
    });

    // Home Controller
    Route::controller(home::class)->group(function () {
        Route::get('/home', 'load');
    });

    // Main Controller
    Route::controller(main::class)->group(function () {
        Route::get('/', 'load');

        Route::get('/register', function() {
            return view('Store.register');
        });
        Route::any('/register-store', 'loadRegisterStore');

        Route::post('/do-register', 'register');
        Route::post('/do-register-store', 'registerStore');
        Route::post('/login', 'login');

        Route::get('/profile', 'loadProfile');
        Route::post('/profile/transaction/{mode}', 'transactionCRUD');

        Route::post('/get-address', 'getAddress');
        Route::post('/get-city', 'getCity');
        Route::post('/get-user', 'getUser');
        Route::post('/add-password', 'setPassword');

        Route::post('/address/{mode}', 'addressCRUD');
        Route::post('/shipment/{mode}', 'shipmentCRUD');
        Route::post('/voucher/{mode}', 'voucherCRUD');
        Route::post('/group/{mode}', 'groupCRUD');
    });

    // Product Controller
    Route::controller(product::class)->group(function () {
        Route::get('/product', 'load');
        Route::get('/product/{product_name}', 'detail');
        Route::post('/product', 'load');
        Route::post('/product/filter', 'filter');
        Route::post('/product/get-prices', 'getPrices');
        Route::post('/product/get-group', 'getGroup');
        Route::post('/product/group/payment', 'loadGroupPayment');
        Route::post('/product/group/join', 'loadJoinGroupPayment');
        Route::post('/product/review', 'reviewProduct');

        Route::get('/seller/{seller_name}/{seller_id}', 'loadSeller');
        Route::post('/seller/filter', 'sellerFilter');
    });

    // Wishlist Controller
    Route::controller(wishlist::class)->group(function () {
        Route::get('/wishlist', 'load');
        Route::post('/wishlist/add-wishlist', 'addWishlist');
        Route::post('/wishlist/wishlist-count', 'wishlistCount');
        Route::post('/wishlist/check-wishlist', 'checkWishlist');
    });

    // Favorite Controller
    Route::controller(favorite::class)->group(function () {
        Route::get('/favorite', 'load');
        Route::post('/favorite/add-favorite', 'addFavorite');
        Route::post('/favorite/favorite-count', 'favoriteCount');
        Route::post('/favorite/check-favorite', 'checkFavorite');
    });
// });
