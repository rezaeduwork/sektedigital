<?php

namespace App\Services\Whatsapp;

use App\Models\WhatsappBotMessage;
use Illuminate\Support\Facades\Log;

class MessageProcessor
{
  protected $sessionManager;
  protected $service;

  public function __construct($sessionManager = new SessionManager())
  {
    $this->sessionManager = $sessionManager;

    // Register parsers in the container on construction
    $this->registerParsers();
  }

  /**
   * Register parser classes in the container for dependency injection
   */
  protected function registerParsers()
  {
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

    app()->bind('App\\Services\\Whatsapp\\MessageParser\\Store', function ($app, $params) {
      return new \App\Services\Whatsapp\MessageParser\Store($params['store'] ?? null);
    });

    app()->bind('App\\Services\\Whatsapp\\MessageParser\\Ppob', function ($app, $params) {
      return new \App\Services\Whatsapp\MessageParser\Ppob(
        $params['store'] ?? null,
        $params['session'] ?? null
      );
    });
  }

  public function processIncomingMessage($messageData, $service)
  {
    // Save the service instance for use throughout this class
    $this->service = $service;

    // Extract message details
    $connectionId = $messageData['connectionId'] ?? null;
    $storeWhatsappId = $service->extractStoreIdFromConnectionId($connectionId);

    if (!$storeWhatsappId) {
      Log::error('WhatsappBot: Invalid connection ID format', [
        'connectionId' => $connectionId
      ]);
      return [
        'success' => false,
        'message' => 'Invalid connection ID format'
      ];
    }

    // Get store from the connection ID
    $store = $service->getStoreFromWhatsappId($storeWhatsappId);

    if (!$store) {
      Log::error('WhatsappBot: Store not found for connection', [
        'connectionId' => $connectionId,
        'storeWhatsappId' => $storeWhatsappId
      ]);
      return [
        'success' => false,
        'message' => 'Store not found for this connection'
      ];
    }

    // Extract message content
    $messages = $messageData['data']['messages'] ?? [];

    foreach ($messages as $message) {
      // Skip if it's an outgoing message
      if ($message['key']['fromMe'] ?? false) {
        continue;
      }

      $phone = $message['key']['remoteJid'] ?? null;

      // Skip if no phone number
      if (!$phone) {
        continue;
      }

      // Skip messages from groups (groups have IDs ending with @g.us)
      if (strpos($phone, '@g.us') !== false) {
        Log::info('WhatsappBot: Skipping message from group chat', [
          'remoteJid' => $phone
        ]);
        continue;
      }

      // Clean up phone number (remove @s.whatsapp.net or similar)
      $phone = preg_replace('/\@.*$/', '', $phone);

      // Only respond to the self number for testing purpose (you can replace with your own number)
      $testNumber = '62895355094422'; // Replace with your test number
      if ($phone !== $testNumber) {
        Log::info('WhatsappBot: Skipping message not from test number', [
          'phone' => $phone,
          'testNumber' => $testNumber
        ]);
        continue;
      }

      // Get message content
      $messageContent = $service->extractMessageContent($message);
      $messageType = $service->getMessageType($message);

      // Log the extracted message content
      Log::debug('WhatsappBot: Extracted message content', [
        'messageContent' => $messageContent,
        'messageType' => $messageType,
        'phoneNumber' => $phone
      ]);

      // Get or create customer session
      $session = $this->sessionManager->getOrCreateCustomerSession($store, $phone, 'browsing');

      // Set the session in the request for use by our parser classes
      request()->merge(['whatsappSession' => $session]);

      // Save the incoming message
      $savedMessage = WhatsappBotMessage::create([
        'store_id' => $store->id,
        'phone_number' => $phone,
        'direction' => 'incoming',
        'message' => $messageContent,
        'message_type' => $messageType,
        'session_id' => $session->session_id,
        'context' => $session->current_state,
      ]);

      // Log the message being processed
      Log::debug('WhatsappBot: Processing message', [
        'messageId' => $savedMessage->id,
        'storeId' => $store->id,
        'currentState' => $session->current_state,
        'messageContent' => $messageContent
      ]);

      // Process the message based on context
      $result = $this->service->handleIncomingMessage($store, $savedMessage, $session);

      // Log the result of processing
      Log::debug('WhatsappBot: Message processing result', [
        'result' => $result,
        'sessionAfter' => [
          'state' => $session->current_state,
          'contextData' => $session->context_data
        ]
      ]);

      return $result;
    }

    return [
      'success' => true,
      'message' => 'No valid messages to process'
    ];
  }

  /**
   * Parse template strings in message content
   *
   * @param string $template The template string with placeholders
   * @param \App\Models\WhatsappCustomerSession $session The current customer session
   * @return string The parsed template
   */
  public function templateParser($template, $session)
  {
    if (empty($template)) {
      return '';
    }

    // Replace store-related variables
    $store = $session->store;
    if ($store) {
      $template = str_replace('{{store_name}}', $store->name, $template);
      $template = str_replace('{{store_phone}}', $store->phone ?? '', $template);
      $template = str_replace('{{store_email}}', $store->email ?? '', $template);
    }

    // Parse complex patterns like {Class.method.param} or {Class.method}
    preg_match_all('/{([A-Za-z0-9]+)\.([A-Za-z0-9]+)(\.([A-Za-z0-9\.]+))?}/', $template, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
      $className = $match[1];
      $methodName = $match[2];
      $paramPath = isset($match[4]) ? $match[4] : null;

      // The full matched placeholder
      $placeholder = $match[0];

      // Check if it's a session context variable reference
      if ($className === 'input' && $paramPath) {
        Log::debug('WhatsappBot: Parsing session context variable', [
          'class' => $className,
          'method' => $methodName,
          'param' => $paramPath
        ]);
        // Replace with session context data if exists
        if (isset($session->context_data[$paramPath])) {
          $template = str_replace($placeholder, $session->context_data[$paramPath], $template);
        } else {
          $template = str_replace($placeholder, "[No data for '$paramPath']", $template);
        }
        continue;
      }

      Log::debug('WhatsappBot: Parsing template', [
        'class' => $className,
      ]);

      // For other parser classes like Product.all or Product.input.query
      $parserClass = "App\\Services\\Whatsapp\\MessageParser\\{$className}";

      if (class_exists($parserClass)) {
        // Check if method exists in the parser class
        if (method_exists($parserClass, $methodName)) {
          try {
            // Instantiate the parser class with the store (and session for some parsers)
            $store = $session->store;
            if (!$store) {
              $template = str_replace($placeholder, "[Store not found]", $template);
              continue;
            }

            try {
              // Try to resolve the parser from the container with the needed parameters
              $parserParams = ['store' => $store, 'session' => $session];

              // Create parser instance from the container
              $parser = app()->makeWith($parserClass, $parserParams);

              // Handle different parameter formats
              if ($methodName === 'input' && $paramPath) {
                // Pass the parameter name directly to the input method
                $paramData = ['param' => $paramPath];
                $parsed = $parser->$methodName($paramData);
              } else if ($methodName === 'find' && is_numeric($paramPath)) {
                // Special case for methods that need a direct numeric parameter
                $parsed = $parser->$methodName($paramPath);
              } else {
                // For simple methods like Product.all
                $parsed = $parser->$methodName();
              }

              $template = str_replace($placeholder, $parsed, $template);
            } catch (\Exception $e) {
              Log::error("Template parser error: {$e->getMessage()}", [
                'class' => $parserClass,
                'method' => $methodName,
                'param' => $paramPath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
              ]);
              $template = str_replace($placeholder, "[Error processing data: {$e->getMessage()}]", $template);
            }
          } catch (\Exception $e) {
            Log::error("Error creating parser instance: {$e->getMessage()}", [
              'class' => $parserClass,
              'method' => $methodName,
              'error' => $e->getMessage(),
              'trace' => $e->getTraceAsString()
            ]);
            $template = str_replace($placeholder, "[Error creating parser instance: {$e->getMessage()}]", $template);
          }
        } else {
          $template = str_replace($placeholder, "[Method '$methodName' not found]", $template);
        }
      } else {
        $template = str_replace($placeholder, "[Parser '$className' not found]", $template);
      }
    }

    return $template;
  }
}
