<?php

namespace App\Services\Whatsapp\MessageParser;

use App\Models\WhatsappCustomerSession;
use App\Models\Store;

class Cart
{
    protected $store;
    protected $session;

    /**
     * Constructor
     *
     * @param Store $store The store
     * @param WhatsappCustomerSession $session The customer session
     */
    public function __construct($store, $session = null)
    {
        $this->store = $store;
        $this->session = $session ?: request()->whatsappSession;
    }

    /**
     * Display the customer's current cart
     *
     * @return string
     */
    public function current()
    {
        if (!$this->session || empty($this->session->cart_data['items'])) {
            return "Your cart is empty.";
        }

        $cartItems = $this->session->cart_data['items'];
        $total = 0;
        $result = "";

        foreach ($cartItems as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;

            $result .= "*ID: {$item['id']}* - {$item['name']}\n";
            $result .= "Price: " . number_format($item['price'], 0, ',', '.') . "\n";
            $result .= "Quantity: {$item['quantity']}\n";
            $result .= "Subtotal: " . number_format($subtotal, 0, ',', '.') . "\n\n";
        }

        $result .= "*Total: " . number_format($total, 0, ',', '.') . "*";

        return $result;
    }

    /**
     * Get the number of items in cart
     *
     * @return string
     */
    public function count()
    {
        if (!$this->session || empty($this->session->cart_data['items'])) {
            return "0";
        }

        $count = 0;
        foreach ($this->session->cart_data['items'] as $item) {
            $count += $item['quantity'];
        }

        return (string) $count;
    }

    /**
     * Get the total value of items in cart
     *
     * @return string
     */
    public function total()
    {
        if (!$this->session || empty($this->session->cart_data['items'])) {
            return "0";
        }

        $total = 0;
        foreach ($this->session->cart_data['items'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return number_format($total, 0, ',', '.');
    }
}
