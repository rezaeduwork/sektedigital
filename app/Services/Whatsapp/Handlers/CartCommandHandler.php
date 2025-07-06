<?php

namespace App\Services\Whatsapp\Handlers;

use App\Models\WhatsappBotCommand;

class CartCommandHandler extends AbstractCommandHandler
{
  /**
   * Execute the cart command
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

    // Get the session data
    $cartData = $this->session->data['cart'] ?? [];

    // If cart is empty
    if (empty($cartData)) {
      $responseTemplate = str_replace(
        "[Cart items will be inserted here]",
        "Your cart is currently empty.\n\nBrowse our products by typing */products* or search for specific items by typing their name.",
        $this->command->response_template
      );

      // Replace store name
      $responseTemplate = str_replace("{{store_name}}", $this->store->name, $responseTemplate);

      // Send the message
      app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage(
        $this->store->id,
        $this->phoneNumber,
        $responseTemplate,
        $this->session
      );

      return [
        'success' => true,
        'message' => 'Cart command handled - empty cart'
      ];
    }

    // Format cart items for display
    $cartText = "";
    $totalAmount = 0;

    foreach ($cartData as $index => $item) {
      $itemTotal = $item['quantity'] * $item['price'];
      $totalAmount += $itemTotal;

      $cartText .= ($index + 1) . ". *{$item['name']}*\n";
      $cartText .= "   Quantity: {$item['quantity']}\n";
      $cartText .= "   Price: " . number_format($item['price'], 0) . "\n";
      $cartText .= "   Subtotal: " . number_format($itemTotal, 0) . "\n\n";
    }

    $cartText .= "Total: *" . number_format($totalAmount, 0) . "*";

    // Replace placeholders in response template
    $responseTemplate = str_replace(
      "[Cart items will be inserted here]",
      $cartText,
      $this->command->response_template
    );

    // Replace store name
    $responseTemplate = str_replace("{{store_name}}", $this->store->name, $responseTemplate);

    // Send the message
    app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage(
      $this->store->id,
      $this->phoneNumber,
      $responseTemplate,
      $this->session
    );

    return [
      'success' => true,
      'message' => 'Cart command handled - cart displayed'
    ];
  }
}
