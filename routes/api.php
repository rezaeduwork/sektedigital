<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

// WhatsApp Gateway API routes
Route::post('/webhook/whatsapp', [\App\Http\Controllers\WebhookController::class, 'whatsappWebhook']);

// WhatsApp Bot webhook route
Route::post('/webhook/whatsapp-bot', [\App\Http\Controllers\API\WhatsappWebhookController::class, 'handleWebhook']);

// Route to return active connections for the WhatsApp Gateway
Route::get('/connections/active', function () {
  $connections = \App\Models\StoreWhatsapp::where('status', 'connected')
    ->get(['id', 'last_connected_at'])
    ->map(function ($item) {
      // Format connectionId as CID_{id}
      return ['connection_id' => 'CID_' . $item->id];
    });

  return response()->json($connections);
});
