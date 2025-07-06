<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Store;
use App\Models\StoreWhatsapp;
use App\Models\WhatsappCustomerSession;
use App\Services\Whatsapp\MessageSender;
use Illuminate\Support\Str;
use Mockery;

class WhatsappTest extends TestCase
{
  use RefreshDatabase;

  /**
   * Test sending a text message via WhatsApp gateway
   *
   * @return void
   */
  public function test_send_text_message()
  {
    // Create a test store
    $store = Store::factory()->create();

    // Create a test WhatsApp connection
    $whatsapp = StoreWhatsapp::create([
      'store_id' => $store->id,
      'status' => 'connected',
      'phone_number' => '6281234567890' // Sample phone number
    ]);

    // Create a test session
    $session = WhatsappCustomerSession::create([
      'store_id' => $store->id,
      'phone_number' => '6287654321098', // Test customer number
      'session_id' => Str::uuid()->toString(),
      'customer_name' => 'Test Customer',
      'cart_data' => ['items' => []],
      'current_state' => 'browsing',
      'context_data' => [],
      'is_active' => true
    ]);

    // Create a mock HTTP client that will be injected into the MessageSender
    $mockClient = Mockery::mock('GuzzleHttp\Client');
    $mockResponse = Mockery::mock('Psr\Http\Message\ResponseInterface');
    $mockResponse->shouldReceive('getStatusCode')->andReturn(200);
    $mockResponse->shouldReceive('getBody')->andReturn('{"success":true}');

    // Set expectations for the mock client
    $mockClient->shouldReceive('post')
      ->once()
      ->withArgs(function ($url, $options) use ($whatsapp) {
        // Verify the URL and request body match expected values
        $expectedUrl = env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000') . '/api/send';
        $expectedConnectionId = 'CID_' . $whatsapp->id;

        return $url === $expectedUrl &&
          $options['json']['connectionId'] === $expectedConnectionId &&
          $options['json']['message'] === 'Test message';
      })
      ->andReturn($mockResponse);

    // Create the message sender with our mock client
    $messageSender = new MessageSender();
    app()->instance('GuzzleHttp\Client', $mockClient);

    // Send the test message
    $result = $messageSender->saveAndSendOutgoingMessage(
      $store->id,
      '6287654321098',
      'Test message',
      $session
    );

    // Assert message was saved to DB
    $this->assertDatabaseHas('whatsapp_bot_messages', [
      'store_id' => $store->id,
      'phone_number' => '6287654321098',
      'direction' => 'outgoing',
      'message' => 'Test message',
      'message_type' => 'text'
    ]);
  }

  /**
   * Test sending an image message via WhatsApp gateway
   *
   * @return void
   */
  public function test_send_image_message()
  {
    // Create a test store
    $store = Store::factory()->create();

    // Create a test WhatsApp connection
    $whatsapp = StoreWhatsapp::create([
      'store_id' => $store->id,
      'status' => 'connected',
      'phone_number' => '6281234567890' // Sample phone number
    ]);

    // Create a test session
    $session = WhatsappCustomerSession::create([
      'store_id' => $store->id,
      'phone_number' => '6287654321098', // Test customer number
      'session_id' => Str::uuid()->toString(),
      'customer_name' => 'Test Customer',
      'cart_data' => ['items' => []],
      'current_state' => 'browsing',
      'context_data' => [],
      'is_active' => true
    ]);

    // Create a mock HTTP client that will be injected into the MessageSender
    $mockClient = Mockery::mock('GuzzleHttp\Client');
    $mockResponse = Mockery::mock('Psr\Http\Message\ResponseInterface');
    $mockResponse->shouldReceive('getStatusCode')->andReturn(200);
    $mockResponse->shouldReceive('getBody')->andReturn('{"success":true}');

    // Set expectations for the mock client
    $mockClient->shouldReceive('post')
      ->once()
      ->withArgs(function ($url, $options) use ($whatsapp) {
        // Verify the URL and request body match expected values
        $expectedUrl = env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000') . '/api/send-image';
        $expectedConnectionId = 'CID_' . $whatsapp->id;
        $testImageUrl = 'https://example.com/test-image.jpg';

        return $url === $expectedUrl &&
          $options['json']['connectionId'] === $expectedConnectionId &&
          $options['json']['imageUrl'] === $testImageUrl &&
          $options['json']['caption'] === 'Test image caption';
      })
      ->andReturn($mockResponse);

    // Create the message sender with our mock client
    $messageSender = new MessageSender();
    app()->instance('GuzzleHttp\Client', $mockClient);

    // Send the test image message
    $result = $messageSender->saveAndSendOutgoingImageMessage(
      $store->id,
      '6287654321098',
      'https://example.com/test-image.jpg',
      'Test image caption',
      $session
    );

    // Assert message was saved to DB
    $this->assertDatabaseHas('whatsapp_bot_messages', [
      'store_id' => $store->id,
      'phone_number' => '6287654321098',
      'direction' => 'outgoing',
      'message' => 'Test image caption',
      'message_type' => 'image'
    ]);
  }

  public function tearDown(): void
  {
    Mockery::close();
    parent::tearDown();
  }
}
