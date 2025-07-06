<?php

namespace App\Services\Whatsapp;

use App\Models\WhatsappBotMessage;
use App\Models\Store;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MessageSender
{
  /**
   * Saves and sends an outgoing message
   *
   * @param int $storeId
   * @param string $phone
   * @param string $message
   * @param \App\Models\WhatsappCustomerSession $session
   * @param string|null $mediaUrl Optional URL to media (image, document, etc.)
   * @param string|null $mediaType Optional media type ('image', 'document', 'video', 'audio')
   * @return \App\Models\WhatsappBotMessage
   */
  public function saveAndSendOutgoingMessage($storeId, $phone, $message, $session, $mediaUrl = null, $mediaType = null)
  {
    // Parse template before saving/sending
    $parsedMessage = app('App\\Services\\Whatsapp\\MessageProcessor')->templateParser($message, $session);

    if (!$parsedMessage) {
      return null; // If parsing fails, do not proceed
    }

    // Determine if this is a media message
    $isMediaMessage = !empty($mediaUrl) && !empty($mediaType);
    $messageType = $isMediaMessage ? $mediaType : 'text';

    // Prepare media data if needed
    $mediaData = $isMediaMessage ? ['url' => $mediaUrl, 'type' => $mediaType] : null;

    // Save the outgoing message to the database
    $botMessage = WhatsappBotMessage::create([
      'store_id' => $storeId,
      'phone_number' => $phone,
      'direction' => 'outgoing',
      'message' => $parsedMessage,
      'message_type' => $messageType,
      'media_data' => $mediaData,
      'session_id' => $session->session_id,
      'context' => $session->current_state,
    ]);

    // Send the message via appropriate gateway method
    if ($isMediaMessage) {
      switch ($mediaType) {
        case 'image':
          $this->sendImageMessageViaGateway($storeId, $phone, $mediaUrl, $parsedMessage);
          break;
        case 'document':
          // If you have document sending functionality
          // $this->sendDocumentMessageViaGateway($storeId, $phone, $mediaUrl, $parsedMessage);
          break;
        case 'video':
          // If you have video sending functionality
          // $this->sendVideoMessageViaGateway($storeId, $phone, $mediaUrl, $parsedMessage);
          break;
        case 'audio':
          // If you have audio sending functionality
          // $this->sendAudioMessageViaGateway($storeId, $phone, $mediaUrl, $parsedMessage);
          break;
        default:
          // Default to text message if media type is not supported
          $this->sendMessageViaGateway($storeId, $phone, $parsedMessage);
      }
    } else {
      // Regular text message
      $this->sendMessageViaGateway($storeId, $phone, $parsedMessage);
    }

    return $botMessage;
  }

  /**
   * Send message via WhatsApp gateway
   *
   * @param int $storeId
   * @param string $phone
   * @param string $message
   * @return bool
   */
  protected function sendMessageViaGateway($storeId, $phone, $message)
  {
    try {
      // Get the store's WhatsApp connection
      $store = \App\Models\Store::findOrFail($storeId);
      $whatsapp = $store->whatsapp;

      if (!$whatsapp || $whatsapp->status !== 'connected') {
        \Illuminate\Support\Facades\Log::error('WhatsappBot: Cannot send message, store WhatsApp not connected', [
          'storeId' => $storeId
        ]);
        return false;
      }

      // Format the connection ID as CID_{id} for the NodeJS gateway
      $connectionId = 'CID_' . $whatsapp->id;

      // Make API request to the NodeJS WhatsApp gateway to send the message
      $gatewayUrl = env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000');
      $client = new \GuzzleHttp\Client();

      $response = $client->post("{$gatewayUrl}/api/send-message", [
        'json' => [
          'connectionId' => $connectionId,
          'number' => $phone,
          'message' => $message
        ],
        'headers' => [
          'Authorization' => 'Bearer ' . env('WHATSAPP_GATEWAY_API_KEY', 'simple_api_token_123'),
          'Content-Type' => 'application/json'
        ]
      ]);

      if ($response->getStatusCode() == 200) {
        \Illuminate\Support\Facades\Log::debug('WhatsappBot: Message sent successfully', [
          'storeId' => $storeId,
          'phone' => $phone,
          'connectionId' => $connectionId
        ]);
        return true;
      }

      \Illuminate\Support\Facades\Log::error('WhatsappBot: Failed to send message via gateway', [
        'storeId' => $storeId,
        'statusCode' => $response->getStatusCode(),
        'response' => (string) $response->getBody()
      ]);

      return false;
    } catch (\Exception $e) {
      \Illuminate\Support\Facades\Log::error('WhatsappBot: Error sending message via gateway', [
        'storeId' => $storeId,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      return false;
    }
  }

  /**
   * Saves and sends an outgoing image message
   *
   * @param int $storeId
   * @param string $phone
   * @param string $imageUrl URL to the image to be sent
   * @param string $caption Optional caption for the image
   * @param \App\Models\WhatsappCustomerSession $session
   * @return \App\Models\WhatsappBotMessage
   */
  public function saveAndSendOutgoingImageMessage($storeId, $phone, $imageUrl, $caption, $session)
  {
    // Use the enhanced saveAndSendOutgoingMessage method with media parameters
    return $this->saveAndSendOutgoingMessage($storeId, $phone, $caption, $session, $imageUrl, 'image');
  }

  /**
   * Send image message via WhatsApp gateway
   *
   * @param int $storeId
   * @param string $phone
   * @param string $imageUrl
   * @param string $caption
   * @return bool
   */
  protected function sendImageMessageViaGateway($storeId, $phone, $imageUrl, $caption)
  {
    try {
      // Get the store's WhatsApp connection
      $store = Store::findOrFail($storeId);
      $whatsapp = $store->whatsapp;

      if (!$whatsapp || $whatsapp->status !== 'connected') {
        Log::error('WhatsappBot: Cannot send image, store WhatsApp not connected', [
          'storeId' => $storeId
        ]);
        return false;
      }

      // Format the connection ID as CID_{id} for the NodeJS gateway
      $connectionId = 'CID_' . $whatsapp->id;

      // Make API request to the NodeJS WhatsApp gateway to send the image
      $gatewayUrl = env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000');
      $client = new Client();

      $response = $client->post("{$gatewayUrl}/api/send-image", [
        'json' => [
          'connectionId' => $connectionId,
          'number' => $phone,
          'image' => $imageUrl,
          'caption' => $caption
        ],
        'headers' => [
          'Authorization' => 'Bearer ' . env('WHATSAPP_GATEWAY_API_KEY', 'simple_api_token_123'),
          'Content-Type' => 'application/json'
        ]
      ]);

      if ($response->getStatusCode() == 200) {
        Log::debug('WhatsappBot: Image sent successfully', [
          'storeId' => $storeId,
          'phone' => $phone
        ]);
        return true;
      }

      Log::error('WhatsappBot: Failed to send image via gateway', [
        'storeId' => $storeId,
        'statusCode' => $response->getStatusCode(),
        'response' => (string) $response->getBody()
      ]);

      return false;
    } catch (\Exception $e) {
      Log::error('WhatsappBot: Error sending image via gateway', [
        'storeId' => $storeId,
        'error' => $e->getMessage()
      ]);

      return false;
    }
  }
}
