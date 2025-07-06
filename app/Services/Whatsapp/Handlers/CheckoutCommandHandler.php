<?php

namespace App\Services\Whatsapp\Handlers;

use App\Models\WhatsappBotCommand;

class CheckoutCommandHandler extends AbstractCommandHandler
{
  /**
   * Execute the checkout command
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
      $message = "🛍️ *Your cart is empty!*\n\n";
      $message .= "Please add products to your cart before checkout.\n";
      $message .= "Browse products by typing */products* or search for specific items by typing their name.";

      // Send the message
      app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage(
        $this->store->id,
        $this->phoneNumber,
        $message,
        $this->session
      );

      return [
        'success' => true,
        'message' => 'Checkout command handled - empty cart'
      ];
    }

    // Update session state to checkout
    $this->session->update([
      'current_state' => 'checkout',
      'checkout_step' => 'customer_info'
    ]);

    // Format cart items for display
    $cartText = "";
    $totalAmount = 0;

    foreach ($cartData as $index => $item) {
      $itemTotal = $item['quantity'] * $item['price'];
      $totalAmount += $itemTotal;

      $cartText .= "- *{$item['name']}* ({$item['quantity']} x " . number_format($item['price'], 0) . ")\n";
    }

    $cartText .= "\nTotal: *" . number_format($totalAmount, 0) . "*";

    // Replace placeholders in response template
    $responseTemplate = $this->command->response_template;

    // Replace store name
    $responseTemplate = str_replace("{{store_name}}", $this->store->name, $responseTemplate);

    // Send step 1 message - confirm order
    $confirmMessage = $responseTemplate . "\n\n";
    $confirmMessage .= "Your order summary:\n\n";
    $confirmMessage .= $cartText . "\n\n";
    $confirmMessage .= "Type *confirm* to proceed or *cancel* to cancel the order.";

    // Send the message
    app('App\\Services\\Whatsapp\\MessageSender')->saveAndSendOutgoingMessage(
      $this->store->id,
      $this->phoneNumber,
      $confirmMessage,
      $this->session
    );

    return [
      'success' => true,
      'message' => 'Checkout command handled - started checkout process'
    ];
  }
}
