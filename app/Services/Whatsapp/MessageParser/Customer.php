<?php

namespace App\Services\Whatsapp\MessageParser;

use App\Models\WhatsappCustomer;
use App\Models\Store;
use App\Models\WhatsappCustomerSession;

class Customer
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
     * Get the customer's name
     *
     * @return string
     */
    public function name()
    {
        if (!$this->session) {
            return "Guest";
        }

        return $this->session->customer_name ?? "Guest";
    }

    /**
     * Get the customer's phone number
     *
     * @return string
     */
    public function phone()
    {
        if (!$this->session) {
            return "";
        }

        return $this->session->phone_number ?? "";
    }    /**
     * Get specific customer information from context data
     *
     * @param array $data Contains the parameter name to look up in context_data
     * @return string
     */
    public function input($data)
    {
        if (!$this->session || empty($data) || !isset($data['param'])) {
            return "";
        }

        $param = $data['param'];
        return $this->session->context_data[$param] ?? "";
    }
}
