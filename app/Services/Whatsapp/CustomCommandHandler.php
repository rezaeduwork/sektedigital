<?php

namespace App\Services\Whatsapp;

use Illuminate\Support\Facades\Log;
use App\Models\WhatsappBotCommand;
use App\Models\WhatsappCustomerSession;

class CustomCommandHandler
{
  public function handleCustomCommand($store, $customCommand, $messageContent, $phone, $session)
  {
    // Initialize response
    $response = null;
    $commandName = $customCommand->command;
    $masterCommand = null;

    Log::debug('WhatsappBot: Handling custom command', [
      'store' => $store->id,
      'command' => $commandName,
      'hasParameters' => !empty($customCommand->parameters),
      'hasMasterCommandId' => (bool)$customCommand->master_command_id,
      'currentState' => $session->current_state
    ]);

    if ($customCommand->state) {
      // update session state here
      $contextData = $session->context_data ?? [];
      $newState = $customCommand->state ?? 'browsing';
      Log::debug('WhatsappBot: Updating session state', [
        'newState' => $newState
      ]);
      $session = app('App\\Services\\Whatsapp\\SessionManager')->updateState($session, $newState, $contextData);
    }

    // session action
    if ($customCommand->category->name === 'Bitnet PPOB') {
      $sessionAction = $this->handlePpobSessionAction($session, $store, $customCommand, $messageContent);
    }

    if (!isset($sessionAction)) {
      app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage($store->id, $phone, "Bot belum di setting.", $session);
      return [
        'success' => true,
        'message' => 'Custom command executed'
      ];
    }

    if (isset($sessionAction['type']) && $sessionAction['type'] == 'return') {
      return $sessionAction;
    }

    // Check if this command has a master command via master_command_id
    if ($customCommand->master_command_id) {
      $masterCommand = $customCommand->masterCommand;

      if ($masterCommand) {
        Log::debug('WhatsappBot: Using master command response template via relationship', [
          'customCommand' => $customCommand->command,
          'masterCommand' => $masterCommand->command
        ]);
        $response = $masterCommand->response_template;
      }
    }
    // If no master_command_id is set, try to find a master command with the same command name
    else if (!$customCommand->is_master) {
      $masterCommand = WhatsappBotCommand::where('command', $commandName)
        ->where('is_master', true)
        ->first();

      if ($masterCommand) {
        Log::debug('WhatsappBot: Found matching master command by name', [
          'customCommand' => $customCommand->command,
          'masterCommand' => $masterCommand->command
        ]);
        $response = $masterCommand->response_template;
      }
    }

    // If no master command found or no response template set throw error to customer
    if (!$response) {
      Log::debug('WhatsappBot: doesnt have master template', [
        'command' => $commandName
      ]);
      $response = "Maaf, sepertinya ada kesalahan.";
      app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage($store->id, $phone, $response, $session);
      return [
        'success' => false,
        'message' => 'No master response template found'
      ];
    }

    // extract session context data from message content
    // $contextData = [];
    // if (!empty($customCommand->parameters)) {
    //   $messageWords = explode(' ', $messageContent);
    //   array_shift($messageWords); // Remove the command from the parameters

    //   $i = 0;
    //   foreach ($customCommand->parameters as $paramName => $paramType) {
    //     if (isset($messageWords[$i])) {
    //       $contextData[$paramName] = $messageWords[$i];
    //     }
    //     $i++;
    //   }
    // }

    // Send the response - template will be parsed in MessageSender
    app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage($store->id, $phone, $response, $session);
    return [
      'success' => true,
      'message' => 'Custom command executed'
    ];
  }
  public function matchCommand($store, $messageContent)
  {
    // check full commands
    $command = WhatsappBotCommand::where('store_id', $store->id)
      ->where('is_master', false)
      ->where('is_active', true)
      ->where('command', $messageContent) // Get the first word of the message
      ->first();

    // if exist return command
    if ($command) {
      return $command;
    }

    // Get ONLY store-specific commands, not master commands
    $command = WhatsappBotCommand::where('store_id', $store->id)
      ->where('is_master', false)
      ->where('is_active', true)
      ->where('command', (explode(' ', $messageContent)[0] ?? '')) // Get the first word of the message
      ->first();

    // verify command and messagecontent has parameters
    if ($command && sizeof(array_values($command->parameters)) > 0) {
      $messageParameters = explode(' ', $messageContent);
      array_shift($messageParameters); // Remove the command from the parameters
      $messageParameters = [implode(' ', $messageParameters)]; // Join the remaining parameters
      Log::debug('WhatsappBot: Checking command parameters', [
        'command' => $command->command,
        'messageParameters' => $messageParameters,
        'commandParameters' => array_values($command->parameters)
      ]);
      if (sizeof($messageParameters) != sizeof(array_values($command->parameters))) {
        return null; // Return null if the number of parameters doesn't match
      }
    }

    return $command;
  }
  public function handleNoCommandMessage($store, $messageContent, $phone, $session)
  {
    // update session state here

    $response = "Maaf, perintah \"$messageContent\" tidak dikenali. Silakan gunakan perintah yang tersedia.";
    app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage($store->id, $phone, $response, $session);
    return [
      'success' => true,
      'message' => 'No command message executed'
    ];
  }
  public function invalidCommand($store, $session, $command, $messageContent, $sendInfo = true)
  {
    $phone = $session->phone_number;
    if ($command === 'help') {
      $sessionContext = $session->context_data ?? [];
      app('App\\Services\\Whatsapp\\SessionManager')->updateState($session, 'browsing', $sessionContext);
    }
    if ($sendInfo) {
      app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage($store->id, $phone, "Maaf, pilihan tidak valid ⚠️\n\nSesi di reset.", $session);
    }
    $existingCustomCommand = WhatsappBotCommand::where('store_id', $store->id)
      ->where('command', $command)
      ->first();
    if (!$existingCustomCommand) {
      Log::debug('WhatsappBot: Invalid command', [
        'store' => $store->id,
        'command' => $command,
        'messageContent' => $messageContent
      ]);
      app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage($store->id, $phone, "Unrecognize error.", $session);
      $this->invalidCommand($store, $session, 'help', 'help', false);
      return [
        'success' => false,
        'type' => 'return',
        'message' => 'Invalid command'
      ];
    }
    $this->handleCustomCommand($store, $existingCustomCommand, $messageContent, $phone, $session);
    return [
      'success' => false,
      'type' => 'return',
      'message' => 'Invalid selection'
    ];
  }
  public function requiredContextData($session, $data)
  {
    $sessionContext = $session->context_data ?? [];
    if (is_array($data)) {
      foreach ($data as $key) {
        if (!isset($sessionContext[$key])) {
          return false;
        }
      }
    } else {
      $keys = explode('.', $data);
      $current = $sessionContext;

      foreach ($keys as $key) {
        if (!isset($current[$key])) {
          return false;
        }
        $current = $current[$key]; // Dive deeper
      }
    }

    return true;
  }
  public function handlePpobSessionAction(WhatsappCustomerSession $session, $store, $customCommand, $messageContent)
  {
    $sessionContext = $session->context_data ?? [];
    $commandName = $customCommand->command;
    $userInput = explode(' ', $messageContent)[1] ?? null;

    if ($session->current_state === 'browsing' || $commandName === 'help') {
      $sessionContext = [];
      $session->context_data = $sessionContext;
      $session->save();
    }

    if (isset($sessionContext['selected_feature'])) {
      $selected_category = ucwords($sessionContext['selected_feature'] === 'game' ? 'games' : $sessionContext['selected_feature']);
    }

    if (isset($sessionContext['brands']) && isset($sessionContext['selected_index'])) {
      $selected_brand = $sessionContext['brands'][$sessionContext['selected_index']] ?? null;
    }

    // Handle special case for state transitions
    if ($session->current_state === 'ppob.select_brand') {
      if ($commandName === 'select') {
        if (!isset($selected_category)) {
          return $this->invalidCommand($store, $session, 'help', 'help');
        }
        // User selected an item from the menu
        if (is_numeric($userInput)) {
          $checkBrand = \App\Models\StoreProductInstant::where('store_id', $store->id)
            ->where('category', $selected_category)
            ->select('brand')
            ->groupBy('brand')
            ->get()->count();
          // Store the selected index in the session context
          if ($userInput > 0 && $userInput <= $checkBrand) {
            $sessionContext['selected_index'] = $userInput;
            $session->context_data = $sessionContext;
            $session->save();
          } else {
            return $this->invalidCommand($store, $session, $sessionContext['selected_feature'], $sessionContext['selected_feature']);
          }
        } else {
          // If no valid index provided, send an error message
          return $this->invalidCommand($store, $session, $sessionContext['selected_feature'], $sessionContext['selected_feature']);
        }
      } else {
        // If user input is not a valid index, return invalid command
        return $this->invalidCommand($store, $session, 'help', 'help');
      }
    } else if ($session->current_state === 'ppob.select_product') {
      if (in_array(false, [isset($selected_category), isset($selected_brand)])) {
        return $this->invalidCommand($store, $session, 'help', 'help');
      }

      if ($commandName === 'p' && $userInput) {
        $product = \App\Models\StoreProductInstant::where('store_id', $store->id)
          ->where('category', $selected_category)
          ->where('brand', $selected_brand)
          ->where('code', $userInput)
          ->first();
        if ($product) {
          // Store the selected product in the session context
          $sessionContext['code'] = $product->code;
          $session->context_data = $sessionContext;
          $session->save();
        } else {
          return $this->invalidCommand($store, $session, 'select', 'select ' . $sessionContext['selected_index']);
        }
      } else {
        if (!$userInput) {
          return $this->invalidCommand($store, $session, 'select', 'select ' . $sessionContext['selected_index']);
        } else {
          return $this->invalidCommand($store, $session, 'help', 'help');
        }
      }
    } else if ($session->current_state === 'ppob.payment') {
      if ($commandName === 'checkout') {
        if (!$userInput) {
          return $this->invalidCommand($store, $session, 'pay', 'pay ' . $sessionContext['ref']);
        }
        if (!isset($session->context_data['payment_channels'][$userInput])) {
          return $this->invalidCommand($store, $session, 'pay', 'pay ' . $sessionContext['ref']);
        }

        // Get the payment method from message content
        $paymentMethod = $userInput;

        // Store the selected payment method in the session context
        $sessionContext['selected_channel_index'] = $userInput;
        $session->context_data = $sessionContext;
        $session->save();

        app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage($store->id, $session->phone_number, "⌛ Tunggu sebentar, Sedang memproses pembayaran ...", $session);
      } else if ($commandName === 'pay') {
        if (!$userInput) {
          return $this->invalidCommand($store, $session, 'p', 'p ' . $sessionContext['code']);
        }
        $sessionContext['ref'] = $userInput;
        $session->context_data = $sessionContext;
        $session->save();

        app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage($store->id, $session->phone_number, "⌛ Tunggu sebentar, Sedang mengambil channel pembayaran ...", $session);
      }
    } else if ($commandName === 'status' && $session->current_state === 'ppob.waiting_payment') {
      // Check if payment is complete using the Ppob parser
      $ppobParser = app()->makeWith('App\\Services\\Whatsapp\\MessageParser\\Ppob', [
        'store' => $store,
        'session' => $session
      ]);

      $statusResult = $ppobParser->transactionStatus();

      // Send the response with the current status
      app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage($store->id, $phone, $statusResult, $session);

      // Check if session state was changed by the parser
      // If payment is still pending, status would still be waiting_payment
      // If payment is completed/failed/expired, status would be reset to null
      if ($session->current_state === 'waiting_payment') {
        Log::debug('WhatsappBot: Payment still pending, staying in waiting_payment state', [
          'store' => $store->id,
          'session' => $session->id,
          'phone' => $phone
        ]);

        return [
          'success' => true,
          'type' => 'return',
          'message' => 'Payment still pending, staying in waiting_payment state'
        ];
      } else {
        Log::debug('WhatsappBot: Payment status updated, transaction complete', [
          'store' => $store->id,
          'session' => $session->id,
          'phone' => $phone,
          'new_state' => $session->current_state
        ]);

        return [
          'success' => true,
          'type' => 'return',
          'message' => 'Payment status checked, transaction complete'
        ];
      }
    }

    Log::debug('WhatsappBot: No Session Check', [
      'command' => $commandName,
      'sessionState' => $session->current_state,
    ]);

    return [
      'success' => true
    ];
  }
}
