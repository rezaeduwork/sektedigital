<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Store;
use App\Models\WhatsappBotCommand;
use App\Models\WhatsappCustomerSession;
use App\Services\Whatsapp\CustomCommandHandler;
use App\Services\Whatsapp\CommandHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class WhatsappCommandTest extends TestCase
{
  use RefreshDatabase;

  protected $store;
  protected $session;
  protected $messageSenderMock;

  public function setUp(): void
  {
    parent::setUp();

    // Create a test store
    $this->store = Store::factory()->create([
      'name' => 'Test Store',
      'slug' => 'test-store'
    ]);

    // Create a test session
    $this->session = WhatsappCustomerSession::create([
      'store_id' => $this->store->id,
      'phone_number' => '123456789',
      'session_id' => 'test-session',
      'current_state' => 'browsing',
      'context_data' => json_encode(['items' => []]),
      'last_activity' => now()
    ]);

    // Mock the MessageSender service
    $this->messageSenderMock = Mockery::mock('App\Services\Whatsapp\MessageSender');
    $this->messageSenderMock->shouldReceive('saveAndSendOutgoingMessage')
      ->andReturnUsing(function ($storeId, $phone, $message, $session) {
        // Just for testing, we don't need to actually send messages
        return true;
      });

    $this->app->instance('App\Services\Whatsapp\MessageSender', $this->messageSenderMock);
  }

  public function tearDown(): void
  {
    Mockery::close();
    parent::tearDown();
  }

  /**
   * Test that custom commands use master command templates when available
   */
  public function test_custom_command_uses_master_template()
  {
    // Create master command
    $masterCommand = WhatsappBotCommand::create([
      'command' => 'help',
      'description' => 'Help command',
      'response_template' => 'Master help template for {{store_name}}',
      'is_active' => true,
      'is_master' => true,
      'parameters' => []
    ]);

    // Create custom command linked to master
    $customCommand = WhatsappBotCommand::create([
      'store_id' => $this->store->id,
      'command' => 'help',
      'description' => 'Custom help',
      'response_template' => 'Custom help template for {{store_name}}',
      'is_active' => true,
      'is_master' => false,
      'master_command_id' => $masterCommand->id,
      'parameters' => []
    ]);

    // Set up expectations for the mock
    $this->messageSenderMock->shouldReceive('saveAndSendOutgoingMessage')
      ->once()
      ->with(
        $this->store->id,
        '123456789',
        'Master help template for Test Store',
        $this->session
      )
      ->andReturn(true);

    // Execute the command handler
    $handler = new CustomCommandHandler();
    $result = $handler->handleCustomCommand($this->store, $customCommand, [], '123456789', $this->session);

    $this->assertTrue($result['success']);
  }

  /**
   * Test that commands with parameters properly replace them in templates
   */
  public function test_command_parameter_replacement()
  {
    // Create master command with parameters
    $masterCommand = WhatsappBotCommand::create([
      'command' => 'greet',
      'description' => 'Greet command',
      'response_template' => 'Hello {{name}} from {{store_name}}!',
      'is_active' => true,
      'is_master' => true,
      'parameters' => ['name']
    ]);

    // Create custom command linked to master
    $customCommand = WhatsappBotCommand::create([
      'store_id' => $this->store->id,
      'command' => 'greet',
      'description' => 'Greet someone',
      'response_template' => 'Custom greeting {{name}}',
      'is_active' => true,
      'is_master' => false,
      'master_command_id' => $masterCommand->id,
      'parameters' => ['name']
    ]);

    // Set up expectations for the mock
    $this->messageSenderMock->shouldReceive('saveAndSendOutgoingMessage')
      ->once()
      ->with(
        $this->store->id,
        '123456789',
        'Hello John from Test Store!',
        $this->session
      )
      ->andReturn(true);

    // Execute the command handler
    $handler = new CustomCommandHandler();
    $result = $handler->handleCustomCommand($this->store, $customCommand, ['John'], '123456789', $this->session);

    $this->assertTrue($result['success']);
  }

  /**
   * Test command matching for phrases like "help me"
   */
  public function test_partial_command_matching()
  {
    // Create a command
    WhatsappBotCommand::create([
      'store_id' => $this->store->id,
      'command' => 'help',
      'description' => 'Help command',
      'response_template' => 'Help info for {{store_name}}',
      'is_active' => true,
      'is_master' => false
    ]);

    // Create the command handler
    $handler = new CommandHandler();

    // Test with "help me please" message
    $result = $handler->matchCommandWithoutPrefix($this->store, 'help me please');

    // This should match the "help" command
    $this->assertEquals('/help me please', $result);
  }

  /**
   * Test that store-specific commands take precedence over global commands
   */
  public function test_store_command_precedence()
  {
    // Create global command
    WhatsappBotCommand::create([
      'store_id' => null, // Global command
      'command' => 'info',
      'description' => 'Global info',
      'response_template' => 'Global info template',
      'is_active' => true,
      'is_master' => false
    ]);

    // Create store-specific command with same name
    WhatsappBotCommand::create([
      'store_id' => $this->store->id,
      'command' => 'info',
      'description' => 'Store info',
      'response_template' => 'Store info template for {{store_name}}',
      'is_active' => true,
      'is_master' => false
    ]);

    // Create a mock message object
    $message = new \stdClass();
    $message->phone_number = '123456789';

    // Set up expectations for the mock - should use store-specific command
    $this->messageSenderMock->shouldReceive('saveAndSendOutgoingMessage')
      ->once()
      ->with(
        $this->store->id,
        '123456789',
        'Store info template for Test Store',
        $this->session
      )
      ->andReturn(true);

    // Execute the command handler
    $handler = new CommandHandler();
    $result = $handler->handleCommand($this->store, 'info', $message, $this->session);

    $this->assertTrue($result['success']);
  }
}
