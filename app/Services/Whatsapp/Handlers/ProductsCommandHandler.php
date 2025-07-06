<?php

namespace App\Services\Whatsapp\Handlers;

class ProductsCommandHandler extends AbstractCommandHandler
{
  /**
   * Execute the products command
   *
   * @param array $params
   * @return array
   */
  protected function execute(array $params = [])
  {
    // This is a sample implementation
    // In a real implementation, you would fetch products and format them

    if (!$this->store) {
      return [
        'success' => false,
        'message' => 'Store context not set'
      ];
    }

    // Get the store's products (limit to 5 for preview)
    $products = $this->store->products()
      ->where('status', 'active')
      ->where('stock', '>', 0)
      ->orderBy('created_at', 'desc')
      ->limit(5)
      ->get();

    // Format products for WhatsApp
    $productsText = "";
    foreach ($products as $index => $product) {
      $productsText .= ($index + 1) . ". *{$product->title}*\n";
      $productsText .= "   Price: " . number_format($product->price, 0) . "\n";
      $productsText .= "   Type */view " . ($index + 1) . "* for details\n\n";
    }

    if (empty($productsText)) {
      $productsText = "No products available at the moment.";
    }

    // Get the response template and replace placeholders
    $responseTemplate = $this->command->response_template;
    $responseTemplate = str_replace("[Products will be dynamically inserted here]", $productsText, $responseTemplate);
    $responseTemplate = str_replace("{{store_name}}", $this->store->name, $responseTemplate);

    return [
      'success' => true,
      'message' => $responseTemplate
    ];
  }
}
