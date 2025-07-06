<?php

namespace App\Services\Whatsapp\MessageParser;

use App\Models\Store;
use App\Models\WhatsappCustomerSession;

class Settings
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
     * Get store name
     *
     * @return string
     */
    public function name()
    {
        return $this->store->name ?? 'Store';
    }

    /**
     * Get store address
     *
     * @return string
     */
    public function address()
    {
        return $this->store->address ?? 'Address not available';
    }

    /**
     * Get store contact information
     *
     * @return string
     */
    public function contact()
    {
        $result = "*{$this->store->name}*\n\n";

        if (!empty($this->store->phone)) {
            $result .= "Phone: {$this->store->phone}\n";
        }

        if (!empty($this->store->email)) {
            $result .= "Email: {$this->store->email}\n";
        }

        if (!empty($this->store->website)) {
            $result .= "Website: {$this->store->website}\n";
        }

        if (!empty($this->store->address)) {
            $result .= "\nAddress:\n{$this->store->address}\n";
        }

        $result .= "\nStore hours: " . ($this->store->hours ?? "Please contact us for store hours");

        return $result;
    }

    /**
     * Get available payment methods
     *
     * @return string
     */
    public function payment()
    {
        $payments = $this->store->payment_methods ?? [];

        if (empty($payments)) {
            return "No payment methods configured for this store.";
        }

        $result = "*Available Payment Methods:*\n\n";

        foreach ($payments as $method) {
            $result .= "- {$method['name']}\n";

            if (!empty($method['description'])) {
                $result .= "  {$method['description']}\n";
            }

            if (!empty($method['account'])) {
                $result .= "  Account: {$method['account']}\n";
            }

            $result .= "\n";
        }

        return $result;
    }

    /**
     * Get shipping methods
     *
     * @return string
     */
    public function shipping()
    {
        $shipping = $this->store->shipping_methods ?? [];

        if (empty($shipping)) {
            return "No shipping methods configured for this store.";
        }

        $result = "*Available Shipping Methods:*\n\n";

        foreach ($shipping as $method) {
            $result .= "- {$method['name']}\n";

            if (!empty($method['price'])) {
                $result .= "  Price: " . number_format($method['price'], 0, ',', '.') . "\n";
            }

            if (!empty($method['description'])) {
                $result .= "  {$method['description']}\n";
            }

            $result .= "\n";
        }

        return $result;
    }

    /**
     * Get a custom store setting by name
     *
     * @param array $data The data containing the setting name
     * @return string
     */
    public function custom($data)
    {
        if (!isset($data['query'])) {
            return "Setting name is required.";
        }

        $settingName = $data['query'];
        $settings = $this->store->settings ?? [];

        if (empty($settings) || !isset($settings[$settingName])) {
            return "Setting '{$settingName}' not found.";
        }

        return $settings[$settingName];
    }
}
