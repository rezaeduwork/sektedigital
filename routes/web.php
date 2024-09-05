<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::prefix('errors')->group(function() {
  Route::get('/unauthenticated', \App\Livewire\Errors\Unauthenticated::class);
});
Route::get('/', \App\Livewire\Home::class);
Route::get('/shop', \App\Livewire\Shop::class);
Route::get('/shop/{path}', \App\Livewire\Shop::class);

Route::middleware('user.auth')->group(function() {
  Route::get('/profile', \App\Livewire\Profile::class);
  Route::get('/cart', \App\Livewire\Cart::class);
  Route::get('/checkout', \App\Livewire\Checkout::class);

  Route::prefix('user')->group(function() {
    Route::get('/profile', \App\Livewire\User\Profile::class);
    Route::get('/store', \App\Livewire\Components\AccountStore::class);
    Route::get('/wallet', \App\Livewire\User\Wallet::class);
    Route::prefix('transaction')->group(function() {
      Route::get('/', \App\Livewire\User\Transaction::class);
    });
  });

  Route::middleware('user.hasstore')->prefix('store')->group(function() {
    Route::get('/', \App\Livewire\Store\Dashboard::class);
    Route::get('/profile', \App\Livewire\Store\Profile::class);
    Route::get('/setting', \App\Livewire\Store\Setting::class);

    Route::prefix('product')->group(function() {
      Route::get('/', \App\Livewire\Store\Product\Index::class);
      Route::get('create', \App\Livewire\Store\Product\Create::class);
      Route::get('edit/{slug}', \App\Livewire\Store\Product\Edit::class);
    });
    Route::prefix('transaction')->group(function() {
      Route::get('history', \App\Livewire\Store\Transaction\History::class);
    });
  });
});

Route::middleware('user.admin')->prefix('admin')->group(function() {
  Route::get('/', \App\Livewire\Admin\Dashboard::class);
  Route::get('/account/{id}', \App\Livewire\Admin\AccountDetail::class);
  Route::get('/account', \App\Livewire\Admin\Account::class);
});

Route::get('/{slug}', \App\Livewire\ProductDetail::class);
