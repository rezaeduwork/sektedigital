<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Whatsapp\MessageSender;
use App\Models\Store;
use App\Models\WhatsappCustomerSession;
use Illuminate\Support\Str;

class TestWhatsappMessage extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'whatsapp:test-message
                            {--s|store= : The ID of the store to use}
                            {--t|type=text : The type of message (text or image)}
                            {--p|phone= : The phone number to send the message to}
                            {--m|message= : The message text to send}
                            {--i|image= : The image URL to send (for image type)}
                            {--c|caption= : The caption for the image (for image type)}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Send a test WhatsApp message';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    // Get options
    $storeId = $this->option('store') ?? 1;
    $type = $this->option('type');
    $phone = $this->option('phone');
    $message = $this->option('message');
    $imageUrl = $this->option('image');
    $caption = $this->option('caption');

    // Validate options
    if (!$phone) {
      $phone = $this->ask('Enter the phone number to send the message to');
    }

    if ($type === 'text' && !$message) {
      $message = $this->ask('Enter the message text');
    }

    if ($type === 'image' && !$imageUrl) {
      $imageUrl = $this->ask('Enter the image URL');
    }

    // Get the store
    $store = Store::find($storeId);
    if (!$store) {
      $this->error("Store ID {$storeId} not found.");
      return 1;
    }

    // Check if WhatsApp is connected for this store
    $whatsapp = $store->whatsapp;
    if (!$whatsapp || $whatsapp->status !== 'connected') {
      $this->error("WhatsApp is not connected for store '{$store->name}'.");
      $this->line("Please connect WhatsApp for this store in the admin panel first.");
      return 1;
    }

    // Get or create a test session
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

    // Send the message
    try {
      $this->info("Sending {$type} message to {$phone}...");

      if ($type === 'text') {
        $result = $messageSender->saveAndSendOutgoingMessage($store->id, $phone, $message, $session);
        $this->info("Message sent successfully! Message ID: {$result->id}");
      } elseif ($type === 'image') {
        $result = $messageSender->saveAndSendOutgoingImageMessage($store->id, $phone, $imageUrl, $caption ?? '', $session);
        $this->info("Image sent successfully! Message ID: {$result->id}");
      } else {
        $this->error("Invalid message type: {$type}");
        return 1;
      }

      return 0;
    } catch (\Exception $e) {
      $this->error("Error: " . $e->getMessage());
      $this->line($e->getTraceAsString());
      return 1;
    }
  }
}
