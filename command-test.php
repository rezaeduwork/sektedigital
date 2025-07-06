<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Store;
use App\Models\WhatsappBotCommand;
use App\Models\WhatsappCustomerSession;
use App\Services\Whatsapp\CustomCommandHandler;
use App\Services\Whatsapp\CommandHandler;

echo "Starting WhatsApp Bot Command Test\n";
echo "================================\n\n";

// Get a store
$store = Store::first();
if (!$store) {
  echo "No store found! Please ensure you have at least one store in the database.\n";
  exit;
}

echo "Using store: {$store->name} (ID: {$store->id})\n\n";

// Create or get a test session
$session = WhatsappCustomerSession::firstOrCreate(
  [
    'phone_number' => '1234567890',
    'store_id' => $store->id
  ],
  [
    'session_id' => 'test-session-' . time(),
    'current_state' => 'browsing',
    'context_data' => json_encode(['items' => []]),
    'last_activity' => now()
  ]
);

echo "Using session: {$session->session_id}\n\n";

// Check for master and custom commands
$masterCommands = WhatsappBotCommand::where('is_master', true)->get();
echo "Master commands in database: " . $masterCommands->count() . "\n";

$customCommands = WhatsappBotCommand::where('is_master', false)
  ->where('store_id', $store->id)
  ->get();
echo "Custom commands for this store: " . $customCommands->count() . "\n\n";

// Create test commands if they don't exist
if ($masterCommands->count() == 0) {
  echo "Creating test master command: 'help'\n";
  WhatsappBotCommand::create([
    'command' => 'help',
    'description' => 'Help command',
    'response_template' => 'Master help template for {{store_name}} - USE THIS TEMPLATE',
    'is_active' => true,
    'is_master' => true,
    'display_order' => 1,
    'parameters' => []
  ]);
}

$helpCommand = WhatsappBotCommand::where('command', 'help')
  ->where('is_master', true)
  ->first();

$testCustomCommand = WhatsappBotCommand::where('command', 'help')
  ->where('store_id', $store->id)
  ->first();

if (!$testCustomCommand && $helpCommand) {
  echo "Creating test custom command linked to master 'help'\n";
  WhatsappBotCommand::create([
    'store_id' => $store->id,
    'command' => 'help',
    'description' => 'Custom help',
    'response_template' => 'CUSTOM HELP TEMPLATE (should not use this)',
    'is_active' => true,
    'is_master' => false,
    'master_command_id' => $helpCommand->id,
    'parameters' => []
  ]);
} else if ($testCustomCommand && !$testCustomCommand->master_command_id && $helpCommand) {
  echo "Linking existing help command to master\n";
  $testCustomCommand->update([
    'master_command_id' => $helpCommand->id
  ]);
}

// Create a command with parameters
$greetMaster = WhatsappBotCommand::where('command', 'greet')
  ->where('is_master', true)
  ->first();

if (!$greetMaster) {
  echo "Creating test master command: 'greet' with parameters\n";
  $greetMaster = WhatsappBotCommand::create([
    'command' => 'greet',
    'description' => 'Greet command',
    'response_template' => 'Hello {{name}} from {{store_name}}!',
    'is_active' => true,
    'is_master' => true,
    'display_order' => 2,
    'parameters' => ['name']
  ]);
}

$greetCustom = WhatsappBotCommand::where('command', 'greet')
  ->where('store_id', $store->id)
  ->first();

if (!$greetCustom && $greetMaster) {
  echo "Creating test custom command linked to master 'greet'\n";
  WhatsappBotCommand::create([
    'store_id' => $store->id,
    'command' => 'greet',
    'description' => 'Custom greeting',
    'response_template' => 'CUSTOM GREETING TEMPLATE (should not use this)',
    'is_active' => true,
    'is_master' => false,
    'master_command_id' => $greetMaster->id,
    'parameters' => ['name']
  ]);
}

// Reload commands to ensure we have the latest data
$customCommands = WhatsappBotCommand::where('is_master', false)
  ->where('store_id', $store->id)
  ->get();

echo "\nCustom commands for testing:\n";
foreach ($customCommands as $cmd) {
  $masterName = $cmd->masterCommand ? $cmd->masterCommand->command : 'None';
  echo "- {$cmd->command} (Master: {$masterName})\n";
}

echo "\n================================\n";
echo "Running command tests\n";
echo "================================\n\n";

// Get command instances
$commandHandler = app(CommandHandler::class);
$customCommandHandler = app(CustomCommandHandler::class);

class MockMessage
{
  public $phone_number = '1234567890';
}
$mockMessage = new MockMessage();

// Test 1: Help Command
echo "TEST 1: Testing '/help' command\n";
$result = $commandHandler->handleCommand($store, '/help', $mockMessage, $session);
echo "Result: " . json_encode($result) . "\n\n";

// Test 2: Command match with phrase like "help me"
echo "TEST 2: Testing 'help me' phrase\n";
$commandMatch = $commandHandler->matchCommandWithoutPrefix($store, 'help me please');
echo "Command match: " . ($commandMatch ? $commandMatch : 'No match') . "\n";
if ($commandMatch) {
  $result = $commandHandler->handleCommand($store, $commandMatch, $mockMessage, $session);
  echo "Result: " . json_encode($result) . "\n";
}
echo "\n";

// Test 3: Greeting with parameters
echo "TEST 3: Testing '/greet John' command with parameters\n";
$result = $commandHandler->handleCommand($store, '/greet John', $mockMessage, $session);
echo "Result: " . json_encode($result) . "\n\n";

echo "Tests completed!\n";
