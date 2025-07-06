<?php

namespace App\Services\Whatsapp\Handlers;

use App\Models\WhatsappBotCommand;
use App\Models\Store;

abstract class AbstractCommandHandler implements CommandHandlerInterface
{
  /**
   * Store instance for context
   *
   * @var Store
   */
  protected $store;

  /**
   * WhatsappBotCommand instance
   *
   * @var WhatsappBotCommand
   */
  protected $command;

  /**
   * Phone number of the customer
   *
   * @var string
   */
  protected $phoneNumber;

  /**
   * Session data
   *
   * @var array
   */
  protected $session;

  /**
   * Set the context for the handler
   *
   * @param Store $store
   * @param string $phoneNumber
   * @param array $session
   * @return $this
   */
  public function withContext(Store $store, string $phoneNumber, $session)
  {
    $this->store = $store;
    $this->phoneNumber = $phoneNumber;
    $this->session = $session;

    return $this;
  }

  /**
   * Handle method implementation from interface
   *
   * @param WhatsappBotCommand $command
   * @param array $params
   * @return mixed
   */
  public function handle(WhatsappBotCommand $command, array $params = [])
  {
    $this->command = $command;

    return $this->execute($params);
  }

  /**
   * Execute the command
   *
   * @param array $params
   * @return mixed
   */
  abstract protected function execute(array $params = []);
}
