<?php

namespace App\Services\Whatsapp\MessageParser;

use App\Models\Store;
use App\Models\Order as OrderModel;
use App\Models\WhatsappCustomerSession;
use Illuminate\Support\Facades\Log;

class Order
{
    protected $store;
    protected $session;

    /**
     * Constructor
     *
     * @param Store $store The store
     * @param WhatsappCustomerSession $session Optional customer session
     */
    public function __construct($store, $session = null)
    {
        $this->store = $store;
        $this->session = $session ?: request()->whatsappSession;
    }

    /**
     * Get last order status for the customer
     *
     * @return string
     */
    public function status()
    {
        if (!$this->session) {
            return "No session available.";
        }

        $phoneNumber = $this->session->phone_number;

        // Get the most recent order for this customer
        $order = OrderModel::where('store_id', $this->store->id)
            ->where('customer_phone', $phoneNumber)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$order) {
            return "You have no orders yet.";
        }

        $status = ucfirst($order->status);
        return "Your latest order #{$order->id} status: *{$status}*";
    }

    /**
     * Get customer's order history
     *
     * @return string
     */
    public function history()
    {
        if (!$this->session) {
            return "No session available.";
        }

        $phoneNumber = $this->session->phone_number;

        // Get recent orders for this customer
        $orders = OrderModel::where('store_id', $this->store->id)
            ->where('customer_phone', $phoneNumber)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        if ($orders->isEmpty()) {
            return "You have no order history with us.";
        }

        $result = "*Your recent orders:*\n\n";

        foreach ($orders as $order) {
            $status = ucfirst($order->status);
            $date = date('d M Y', strtotime($order->created_at));
            $result .= "Order #{$order->id} - {$date}\n";
            $result .= "Status: {$status}\n";
            $result .= "Total: " . number_format($order->total_amount, 0, ',', '.') . "\n\n";
        }

        return $result;
    }

    /**
     * Get details for a specific order by ID
     *
     * @param array $data The data containing the order ID
     * @return string
     */
    public function detail($data)
    {
        if (!$this->session) {
            return "No session available.";
        }

        if (!isset($data['query']) || !is_numeric($data['query'])) {
            return "Please provide a valid order ID.";
        }

        $orderId = $data['query'];
        $phoneNumber = $this->session->phone_number;

        // Get the specific order
        $order = OrderModel::where('store_id', $this->store->id)
            ->where('id', $orderId)
            ->where('customer_phone', $phoneNumber)
            ->first();

        if (!$order) {
            return "Order #{$orderId} not found in your order history.";
        }

        $result = "*Order #{$order->id} Details*\n";
        $result .= "Date: " . date('d M Y H:i', strtotime($order->created_at)) . "\n";
        $result .= "Status: " . ucfirst($order->status) . "\n";
        $result .= "Total: " . number_format($order->total_amount, 0, ',', '.') . "\n\n";

        if (!empty($order->items)) {
            $result .= "*Items:*\n";
            foreach ($order->items as $item) {
                $result .= "- {$item['quantity']}x {$item['name']}\n";
                $result .= "  " . number_format($item['price'], 0, ',', '.') . " each\n";
            }
        }

        if (!empty($order->shipping_address)) {
            $result .= "\n*Shipping Address:*\n";
            $result .= $order->shipping_address . "\n";
        }

        return $result;
    }
}
