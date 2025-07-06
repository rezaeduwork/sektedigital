<?php

namespace App\Services\Whatsapp\Handlers;

use App\Models\WhatsappBotCommand;

interface CommandHandlerInterface
{
  /**
   * Handle a command execution
   *
   * @param WhatsappBotCommand $command The command model
   * @param array $params Parameters passed to the handler
   * @return mixed The result of the command execution
   */
  public function handle(WhatsappBotCommand $command, array $params = []);
}
