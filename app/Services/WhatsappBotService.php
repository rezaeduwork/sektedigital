<?php

namespace App\Services;

use App\Models\Store;
use App\Models\Product;
use App\Models\WhatsappBotMessage;
use App\Models\WhatsappBotCommand;
use App\Models\WhatsappCustomerSession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\Whatsapp\MessageProcessor;
use App\Services\Whatsapp\SessionManager;
use App\Services\Whatsapp\MessageSender;
use App\Services\Whatsapp\CustomCommandHandler;
use Illuminate\Support\Facades\Http;

class WhatsappBotService
{
  public $messageProcessor;
  public $sessionManager;
  public $messageSender;
  public $customCommandHandler;

  public function __construct()
  {
    // Initialize basic services first
    $this->sessionManager = new SessionManager();
    $this->messageSender = new MessageSender();

    // Register in the app container for dependency injection
    app()->instance('App\\Services\\Whatsapp\\MessageSender', $this->messageSender);
    app()->instance('App\\Services\\Whatsapp\\SessionManager', $this->sessionManager);

    // Initialize services that depend on the basic services
    $this->customCommandHandler = new CustomCommandHandler();

    // Initialize message processor last
    $this->messageProcessor = new MessageProcessor($this->sessionManager);

    // Register parser classes in the container with no specific instance
    // They will be instantiated as needed with the correct store and session
    app()->bind('App\\Services\\Whatsapp\\MessageParser\\Product', function ($app, $params) {
      return new \App\Services\Whatsapp\MessageParser\Product($params['store'] ?? null);
    });

    app()->bind('App\\Services\\Whatsapp\\MessageParser\\Cart', function ($app, $params) {
      return new \App\Services\Whatsapp\MessageParser\Cart(
        $params['store'] ?? null,
        $params['session'] ?? null
      );
    });

    app()->bind('App\\Services\\Whatsapp\\MessageParser\\Customer', function ($app, $params) {
      return new \App\Services\Whatsapp\MessageParser\Customer(
        $params['store'] ?? null,
        $params['session'] ?? null
      );
    });

    app()->bind('App\\Services\\Whatsapp\\MessageParser\\Formatter', function ($app, $params) {
      return new \App\Services\Whatsapp\MessageParser\Formatter($params['store'] ?? null);
    });

    app()->bind('App\\Services\\Whatsapp\\MessageParser\\Order', function ($app, $params) {
      return new \App\Services\Whatsapp\MessageParser\Order(
        $params['store'] ?? null,
        $params['session'] ?? null
      );
    });

    app()->bind('App\\Services\\Whatsapp\\MessageParser\\Settings', function ($app, $params) {
      return new \App\Services\Whatsapp\MessageParser\Settings(
        $params['store'] ?? null,
        $params['session'] ?? null
      );
    });

    app()->bind('App\\Services\\Whatsapp\\MessageParser\\Template', function ($app, $params) {
      return new \App\Services\Whatsapp\MessageParser\Template(
        $params['store'] ?? null,
        $params['session'] ?? null
      );
    });

    app()->bind('App\\Services\\Whatsapp\\MessageParser\\Store', function ($app, $params) {
      return new \App\Services\Whatsapp\MessageParser\Store($params['store'] ?? null);
    });
  }

  /**
   * Process an incoming WhatsApp message
   *
   * @param array $messageData
   * @return array
   */
  public function processIncomingMessage($messageData)
  {
    return $this->messageProcessor->processIncomingMessage($messageData, $this);
  }

  /**
   * Extract store ID from connection ID
   *
   * @param string $connectionId
   * @return int|null
   */
  public function extractStoreIdFromConnectionId($connectionId)
  {
    if (!$connectionId || !Str::startsWith($connectionId, 'CID_')) {
      return null;
    }

    return (int) substr($connectionId, 4);
  }

  /**
   * Get store from WhatsApp ID
   *
   * @param int $whatsappId
   * @return Store|null
   */
  public function getStoreFromWhatsappId($whatsappId)
  {
    $storeWhatsapp = \App\Models\StoreWhatsapp::find($whatsappId);

    if (!$storeWhatsapp) {
      return null;
    }

    return $storeWhatsapp->store;
  }

  /**
   * Extract message content from WhatsApp message object
   *
   * @param array $message
   * @return string
   */
  public function extractMessageContent($message)
  {
    // Check for text message
    if (isset($message['message']['conversation'])) {
      return $message['message']['conversation'];
    }

    // Check for extended text message
    if (isset($message['message']['extendedTextMessage']['text'])) {
      return $message['message']['extendedTextMessage']['text'];
    }

    // Check for button response
    if (isset($message['message']['buttonsResponseMessage']['selectedDisplayText'])) {
      return $message['message']['buttonsResponseMessage']['selectedDisplayText'];
    }

    // Check for list response
    if (isset($message['message']['listResponseMessage']['title'])) {
      return $message['message']['listResponseMessage']['title'];
    }

    // For media messages, return caption if available or a placeholder
    if (isset($message['message']['imageMessage']['caption'])) {
      return $message['message']['imageMessage']['caption'];
    }

    // For other message types that we can't extract text from
    return '[Konten pesan tidak dapat diekstrak]';
  }

  /**
   * Get message type from WhatsApp message object
   *
   * @param array $message
   * @return string
   */
  public function getMessageType($message)
  {
    if (isset($message['message']['conversation']) || isset($message['message']['extendedTextMessage'])) {
      return 'text';
    }

    if (isset($message['message']['imageMessage'])) {
      return 'image';
    }

    if (isset($message['message']['documentMessage'])) {
      return 'document';
    }

    if (isset($message['message']['audioMessage'])) {
      return 'audio';
    }

    if (isset($message['message']['videoMessage'])) {
      return 'video';
    }

    if (isset($message['message']['stickerMessage'])) {
      return 'sticker';
    }

    if (isset($message['message']['locationMessage'])) {
      return 'location';
    }

    if (isset($message['message']['contactMessage'])) {
      return 'contact';
    }

    // Default fallback
    return 'unknown';
  }

  /**
   * Handle incoming message for the store
   *
   * @param Store $store
   * @param $message
   * @param $session
   * @return array
   */
  public function handleIncomingMessage($store, $message, $session)
  {
    $messageContent = strtolower(trim($message->message));

    // Log the incoming message for debugging
    \Illuminate\Support\Facades\Log::debug('WhatsappBot: Processing incoming message', [
      'storeId' => $store->id,
      'messageContent' => $messageContent,
      'sessionState' => $session->current_state
    ]);

    // Check if the store has bot settings
    $botSettings = $store->whatsappSettings()->first();

    // If bot is not active, don't process commands
    if (!$botSettings || !$botSettings->is_bot_active) {
      \Illuminate\Support\Facades\Log::debug('WhatsappBot: Bot is inactive for this store', ['storeId' => $store->id]);
      return [
        'success' => false,
        'message' => 'Bot is inactive for this store'
      ];
    }

    // If this is the first message and store activate welcome message, send welcome message
    if ($session->messages()->count() <= 1 && $botSettings->send_welcome_message) {
      \Illuminate\Support\Facades\Log::debug('WhatsappBot: Sending welcome message', ['messageCount' => $session->messages()->count()]);
      $welcomeMessage = $botSettings->welcome_message;
      $this->messageSender->saveAndSendOutgoingMessage($store->id, $message->phone_number, $welcomeMessage, $session);
    }

    // Check if the message matches any command
    $commandMatch = $this->customCommandHandler->matchCommand($store, $messageContent);
    if ($commandMatch) {
      \Illuminate\Support\Facades\Log::debug('WhatsappBot: Matched command', [
        'originalMessage' => $messageContent,
        'matchedCommand' => $commandMatch
      ]);
      return $this->customCommandHandler->handleCustomCommand($store, $commandMatch, $message->message, $message->phone_number, $session);
    }

    // If no command matched, send error message
    \Illuminate\Support\Facades\Log::debug('WhatsappBot: No command matched', [
      'messageContent' => $messageContent
    ]);
    $messageContentOnly = explode(' ',$message->message)[0] ?? '';
    return $this->customCommandHandler->handleNoCommandMessage($store, $messageContentOnly, $message->phone_number, $session);
  }

  /**
   * Connect whatsapp gateway
   *
   * @param string $name
   * @return WhatsappBotCommand|null
   */
  public function connect($id) {
  try {
    // Format the connection ID as CID_{id} for the NodeJS gateway
    $formattedConnectionId = $id;

    // Make API request to the NodeJS WhatsApp gateway using the formatted connectionId
    $response = Http::post(env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000') . '/api/connection', [
      'connectionId' => $formattedConnectionId
    ]);

    if ($response->successful()) {
      return [
        'success' => true,
        'message' => 'WhatsApp connection initialized successfully.',
        'connectionId' => $formattedConnectionId
      ];
    } else {
      return [
        'success' => false,
        'message' => 'Failed to initialize WhatsApp connection: ' . $response->body()
      ];
    }
  } catch (\Exception $e) {
    return [
      'success' => false,
      'message' => 'Error initializing WhatsApp connection: ' . $e->getMessage()
    ];
  }
  }

  /**
   * Disconnect whatsapp gateway
   *
   * @return WhatsappBotCommand|null
   */
  public function disconnect($id) {
    try {
      // Format the connection ID as CID_{id} for the NodeJS gateway
      $formattedConnectionId = $id;

      // Make API request to disconnect from the gateway using the formatted ID
      $response = Http::delete(env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000') . '/api/connection/' . $formattedConnectionId);

      if ($response->successful()) {
        return [
          'success' => true,
          'message' => 'WhatsApp connection disconnected successfully.',
          'connectionId' => $formattedConnectionId
        ];
      } else {
        return [
          'success' => false,
          'message' => 'Failed to disconnect WhatsApp connection: ' . $response->body()
        ];
      }
    } catch (\Exception $e) {
      return [
        'success' => false,
        'message' => 'Error disconnecting WhatsApp connection: ' . $e->getMessage()
      ];
    }
  }

}
