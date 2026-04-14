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

Route::prefix('errors')->group(function () {
  Route::get('/unauthenticated', \App\Livewire\Errors\Unauthenticated::class);
});
Route::post('/webhook/tripay', [\App\Http\Controllers\WebhookController::class, 'tripayNotification']);
Route::post('/webhook/sakurupiah', [\App\Http\Controllers\WebhookController::class, 'sakurupiahNotification']);
Route::post('/webhook/digiflazz', [\App\Http\Controllers\WebhookController::class, 'digiflazzNotification']);
Route::get('/', \App\Livewire\Home::class);
Route::get('/shop', \App\Livewire\Shop::class);
Route::get('/shop/{path}', \App\Livewire\Shop::class);

Route::middleware('user.auth')->group(function () {
  Route::get('/logout', function () {
    auth()->logout();
    return redirect('/');
  })->name('logout');
  Route::get('/profile', \App\Livewire\Profile::class);
  Route::get('/cart', \App\Livewire\Cart::class);
  Route::get('/checkout', \App\Livewire\Checkout::class);
  Route::get('/chat', \App\Livewire\Chat::class);
  Route::get('/chat/{path}', \App\Livewire\Chat::class)->where('path', '.*');


  Route::prefix('user')->group(function () {
    Route::get('/profile', \App\Livewire\User\Profile::class);
    Route::get('/store', \App\Livewire\Components\AccountStore::class);
    Route::get('/wallet', \App\Livewire\User\Wallet::class)->name('wallet');
    Route::get('/payment/{reference}', \App\Livewire\User\Payment::class)->name('payment.detail');
    // Route::get('/wallet/bank', \App\Livewire\User\WalletBank::class);
    Route::prefix('transaction')->group(function () {
      Route::get('/', \App\Livewire\User\Transaction::class);
    });
  });

  Route::middleware('user.hasstore')->prefix('store')->group(function () {
    Route::get('/', \App\Livewire\Store\Dashboard::class);
    Route::get('/profile', \App\Livewire\Store\Profile::class);
    Route::get('/setting', \App\Livewire\Store\Setting::class);

    Route::prefix('product')->group(function () {
      Route::get('/', \App\Livewire\Store\Product\Index::class);
      Route::get('create', \App\Livewire\Store\Product\Create::class);
      Route::get('edit/{slug}', \App\Livewire\Store\Product\Edit::class);
    });
    Route::prefix('transaction')->group(function () {
      Route::get('history', \App\Livewire\Store\Transaction\History::class);
      Route::get('rating', \App\Livewire\Store\Transaction\Rating::class);
      Route::get('ppob', \App\Livewire\Store\Ppob\TransactionList::class)->name('store.ppob.transactions');
    });
    Route::prefix('ppob')->group(function () {
      Route::get('/', \App\Livewire\Store\Ppob\ManageProducts::class)->name('store.ppob.manage');
      Route::get('/add', \App\Livewire\Store\Ppob\AddProduct::class)->name('store.ppob.add');
      Route::get('/create-transaction/{productId}', \App\Livewire\Store\Ppob\CreateTransaction::class)->name('store.ppob.create-transaction');
    });
    Route::get('whatsapp', \App\Livewire\Store\Whatsapp::class);
    Route::get('whatsapp/bot/commands/import', \App\Livewire\Store\ImportWhatsappBotCommands::class)->name('store.whatsapp-bot-commands.import');
    Route::get('whatsapp/bot/categories', \App\Livewire\Store\WhatsappBotCommandCategories::class)->name('store.whatsapp-bot-command-categories');
    Route::get('whatsapp/bot/documentation', \App\Livewire\Store\WhatsappBotDoc::class);
    Route::get('whatsapp/bot/settings', \App\Livewire\Store\Whatsapp\BotSettings::class);
  });
});

Route::middleware('user.admin')->prefix('admin')->group(function () {
  Route::get('/', \App\Livewire\Admin\Dashboard::class);
  Route::get('/account/{id}', \App\Livewire\Admin\AccountDetail::class);
  Route::get('/account', \App\Livewire\Admin\Account::class);
  Route::get('/product', \App\Livewire\Admin\Product::class);
  Route::get('/product_instant', \App\Livewire\Admin\ProductInstant::class);
  Route::get('/category', \App\Livewire\Admin\Category::class);
  Route::get('/transaction', \App\Livewire\Admin\Transaction::class);
  Route::get('/transaction_instant', \App\Livewire\Admin\TransactionInstant::class);
  Route::get('/withdrawal', \App\Livewire\Admin\Withdrawal::class);
  Route::get('/payment-gateway', \App\Livewire\Admin\PaymentGateway::class);
});

Route::get('s/{id}', \App\Livewire\StoreDetail::class);
Route::get('i/{code}', \App\Livewire\ProductInstant::class);
Route::get('/{slug}', \App\Livewire\ProductDetail::class);
Route::middleware('payment.expired')->prefix('payment')->group(function () {
  Route::get('/{id}/detail', \App\Livewire\PaymentDetail::class);
  Route::get('/{id}', \App\Livewire\Payment::class);
});
