<?php

namespace App\Services\Whatsapp\Handlers;

use App\Models\WhatsappBotCommand;
use App\Models\WhatsappBotCommandCategory;

class HelpCommandHandler extends AbstractCommandHandler
{
  /**
   * Execute the help command
   *
   * @param array $params
   * @return array
   */
  protected function execute(array $params = [])
  {
    if (!$this->store) {
      return [
        'success' => false,
        'message' => 'Store context not set'
      ];
    }

    // Get available commands organized by categories
    $categories = WhatsappBotCommandCategory::where(function ($query) {
      $query->where('store_id', $this->store->id)
        ->orWhere('is_master', true);
    })
      ->where('is_active', true)
      ->orderBy('display_order')
      ->get();

    // Get uncategorized commands
    $uncategorizedCommands = WhatsappBotCommand::where(function ($query) {
      $query->where('store_id', $this->store->id)
        ->orWhereNull('store_id');
    })
      ->where('is_active', true)
      ->whereNull('category_id')
      ->orderBy('display_order')
      ->get();

    // Format the help message
    $helpMessage = "📱 *Available Commands for {$this->store->name}* 📱\n\n";

    // Add commands by category
    foreach ($categories as $category) {
      $commands = WhatsappBotCommand::where(function ($query) {
        $query->where('store_id', $this->store->id)
          ->orWhereNull('store_id');
      })
        ->where('category_id', $category->id)
        ->where('is_active', true)
        ->orderBy('display_order')
        ->get();

      if ($commands->count() > 0) {
        $helpMessage .= "*{$category->name}*\n";

        foreach ($commands as $command) {
          $helpMessage .= "- */{$command->command}* - {$command->description}\n";
        }

        $helpMessage .= "\n";
      }
    }

    // Add uncategorized commands if any
    if ($uncategorizedCommands->count() > 0) {
      $helpMessage .= "*Other Commands*\n";

      foreach ($uncategorizedCommands as $command) {
        $helpMessage .= "- */{$command->command}* - {$command->description}\n";
      }

      $helpMessage .= "\n";
    }

    $helpMessage .= "You can also simply type what you're looking for to search our products!\n";
    $helpMessage .= "Need further assistance? Contact us at {$this->store->email}";

    // Send the message
    app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage(
      $this->store->id,
      $this->phoneNumber,
      $helpMessage,
      $this->session
    );

    return [
      'success' => true,
      'message' => 'Help command handled'
    ];
  }
}
