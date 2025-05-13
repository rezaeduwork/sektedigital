<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreWhatsapp;
use App\Services\WhatsappBotService;
use Illuminate\Support\Facades\Log;

class WhatsappWebhookController extends Controller
{
  protected $whatsappBotService;

  public function __construct(WhatsappBotService $whatsappBotService)
  {
    $this->whatsappBotService = $whatsappBotService;
  }

  /**
   * Handle incoming webhooks from WhatsApp Gateway
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function handleWebhook(Request $request)
  {
    try {
      // Log webhook received
      Log::debug('WhatsApp webhook received', [
        'payload' => $request->all()
      ]);

      $type = $request->input('type');
      $connectionId = $request->input('connectionId');
      $data = $request->input('data');

      if (!$type || !$connectionId) {
        return response()->json(['success' => false, 'message' => 'Missing required parameters'], 400);
      }

      // Extract StoreWhatsapp ID from connection ID
      $storeWhatsappId = $this->extractStoreIdFromConnectionId($connectionId);

      if (!$storeWhatsappId) {
        return response()->json(['success' => false, 'message' => 'Invalid connection ID'], 400);
      }

      // Get the StoreWhatsapp record
      $storeWhatsapp = StoreWhatsapp::find($storeWhatsappId);

      if (!$storeWhatsapp) {
        return response()->json(['success' => false, 'message' => 'Store WhatsApp not found'], 404);
      }

      // Handle different webhook types
      switch ($type) {
        case 'qr_code':
          return $this->handleQrCode($storeWhatsapp, $data);

        case 'connection_update':
          return $this->handleConnectionUpdate($storeWhatsapp, $data);

        case 'incoming_message':
          return $this->handleIncomingMessage($request->all());

        case 'message_sent':
          return response()->json(['success' => true, 'message' => 'Message sent notification received']);

        case 'message_failure':
          return response()->json(['success' => true, 'message' => 'Message failure notification received']);

        default:
          return response()->json(['success' => true, 'message' => "Unknown webhook type: {$type}"]);
      }
    } catch (\Exception $e) {
      Log::error('Error handling WhatsApp webhook', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      return response()->json(['success' => false, 'message' => 'Error processing webhook: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Handle QR code webhook
   *
   * @param StoreWhatsapp $storeWhatsapp
   * @param array $data
   * @return \Illuminate\Http\JsonResponse
   */
  protected function handleQrCode($storeWhatsapp, $data)
  {
    try {
      $qrCode = $data['qr'] ?? null;

      if (!$qrCode) {
        return response()->json(['success' => false, 'message' => 'QR code missing from payload'], 400);
      }

      // Update the store WhatsApp record with the new QR code
      $storeWhatsapp->update([
        'qr_code' => $qrCode,
        'status' => 'connecting'
      ]);

      return response()->json(['success' => true, 'message' => 'QR code updated']);
    } catch (\Exception $e) {
      Log::error('Error handling QR code webhook', [
        'error' => $e->getMessage(),
        'storeWhatsappId' => $storeWhatsapp->id
      ]);

      return response()->json(['success' => false, 'message' => 'Error handling QR code: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Handle connection update webhook
   *
   * @param StoreWhatsapp $storeWhatsapp
   * @param array $data
   * @return \Illuminate\Http\JsonResponse
   */
  protected function handleConnectionUpdate($storeWhatsapp, $data)
  {
    try {
      $status = $data['status'] ?? null;
      $user = $data['user'] ?? null;

      if (!$status) {
        return response()->json(['success' => false, 'message' => 'Status missing from payload'], 400);
      }

      // Map gateway status to our status
      $statusMap = [
        'connected' => 'connected',
        'disconnected' => 'disconnected',
        'connecting' => 'connecting'
      ];

      $mappedStatus = $statusMap[$status] ?? 'disconnected';

      // Update the store WhatsApp record with the new status
      $storeWhatsapp->status = $mappedStatus;

      // If we have user data (meaning successfully connected), save the phone number
      if ($status === 'connected' && $user && isset($user['id'])) {
        $phoneNumber = preg_replace('/\@.*$/', '', $user['id']);
        $storeWhatsapp->phone_number = $phoneNumber;
        $storeWhatsapp->last_connected_at = now();
      }

      // If disconnected, clear the QR code
      if ($status === 'disconnected') {
        $storeWhatsapp->qr_code = null;
      }

      $storeWhatsapp->save();

      return response()->json(['success' => true, 'message' => 'Connection status updated']);
    } catch (\Exception $e) {
      Log::error('Error handling connection update webhook', [
        'error' => $e->getMessage(),
        'storeWhatsappId' => $storeWhatsapp->id
      ]);

      return response()->json(['success' => false, 'message' => 'Error handling connection update: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Handle incoming message webhook
   *
   * @param array $webhookData
   * @return \Illuminate\Http\JsonResponse
   */
  protected function handleIncomingMessage($webhookData)
  {
    try {
      // Process the incoming message using our bot service
      $result = $this->whatsappBotService->processIncomingMessage($webhookData);

      if (!$result['success']) {
        Log::error('Error processing incoming message', [
          'error' => $result['message']
        ]);

        return response()->json([
          'success' => false,
          'message' => 'Error processing incoming message: ' . $result['message']
        ], 500);
      }

      return response()->json([
        'success' => true,
        'message' => 'Incoming message processed successfully'
      ]);
    } catch (\Exception $e) {
      Log::error('Error handling incoming message webhook', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      return response()->json(['success' => false, 'message' => 'Error handling incoming message: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Extract store ID from connection ID
   * Connection IDs are in the format "CID_{id}"
   *
   * @param string $connectionId
   * @return int|null
   */
  protected function extractStoreIdFromConnectionId($connectionId)
  {
    if (!$connectionId || !str_starts_with($connectionId, 'CID_')) {
      return null;
    }

    return (int) substr($connectionId, 4);
  }
}
