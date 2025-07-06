<?php

/**
 * WhatsApp Gateway Integration Test Script
 *
 * This script tests the WhatsApp gateway integration by sending test messages.
 * It can be run from the command line to verify the functionality of the MessageSender class.
 *
 * Usage: php whatsapp-test.php [command] [parameters]
 *
 * Commands:
 *   send-text [phone] [message]   - Send a text message to a phone number
 *   send-image [phone] [imageUrl] [caption] - Send an image message to a phone number
 *
 * Example: php whatsapp-test.php send-text 6281234567890 "Hello from WhatsApp Bot!"
 */

// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\Whatsapp\MessageSender;
use App\Models\Store;
use App\Models\WhatsappCustomerSession;
use Illuminate\Support\Str;

// Default parameters
$defaultStoreId = 1; // Use the first store in database
$defaultPhone = '628123456789'; // Default test phone number

// Process command line arguments
if ($argc < 2) {
  echo "Usage: php whatsapp-test.php [command] [parameters]\n";
  echo "Commands:\n";
  echo "  send-text [phone] [message]   - Send a text message\n";
  echo "  send-image [phone] [imageUrl] [caption] - Send an image message\n";
  exit(1);
}

$command = $argv[1];

// Get the store to test with
$store = Store::find($defaultStoreId);
if (!$store) {
  echo "Error: Store ID {$defaultStoreId} not found.\n";
  echo "Please create a store first or update the script with a valid store ID.\n";
  exit(1);
}

// Check if WhatsApp is connected for this store
$whatsapp = $store->whatsapp;
if (!$whatsapp || $whatsapp->status !== 'connected') {
  echo "Error: WhatsApp is not connected for store '{$store->name}'.\n";
  echo "Please connect WhatsApp for this store in the admin panel first.\n";
  exit(1);
}

// Get or create a test session
$phone = $argv[2] ?? $defaultPhone;
$session = WhatsappCustomerSession::firstOrCreate(
  [
    'store_id' => $store->id,
    'phone_number' => $phone
  ],
  [
    'session_id' => Str::uuid()->toString(),
    'customer_name' => 'Test Customer',
    'cart_data' => ['items' => []],
    'current_state' => 'browsing',
    'context_data' => [],
    'is_active' => true
  ]
);

// Create message sender
$messageSender = new MessageSender();

// Execute the command
try {
  switch ($command) {
    case 'send-text':
      if ($argc < 4) {
        echo "Usage: php whatsapp-test.php send-text [phone] [message]\n";
        exit(1);
      }
      $message = $argv[3];
      echo "Sending text message to {$phone}...\n";
      $result = $messageSender->saveAndSendOutgoingMessage($store->id, $phone, $message, $session);
      echo "Message sent successfully! Message ID: {$result->id}\n";
      break;

    case 'send-image':
      if ($argc < 5) {
        echo "Usage: php whatsapp-test.php send-image [phone] [imageUrl] [caption]\n";
        exit(1);
      }
      $imageUrl = $argv[3];
      $caption = $argv[4] ?? '';
      echo "Sending image message to {$phone}...\n";
      $result = $messageSender->saveAndSendOutgoingImageMessage($store->id, $phone, $imageUrl, $caption, $session);
      echo "Image sent successfully! Message ID: {$result->id}\n";
      break;

    default:
      echo "Unknown command: {$command}\n";
      echo "Usage: php whatsapp-test.php [command] [parameters]\n";
      echo "Commands:\n";
      echo "  send-text [phone] [message]   - Send a text message\n";
      echo "  send-image [phone] [imageUrl] [caption] - Send an image message\n";
      exit(1);
  }
} catch (\Exception $e) {
  echo "Error: " . $e->getMessage() . "\n";
  echo $e->getTraceAsString() . "\n";
  exit(1);
}
