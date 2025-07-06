<?php

namespace App\Services\Whatsapp\MessageParser;

use App\Models\Store as StoreModel;
use Carbon\Carbon;

class Store
{
    protected $store;

    /**
     * Constructor
     *
     * @param StoreModel $store The store to retrieve information from
     */
    public function __construct($store)
    {
        $this->store = $store;
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
     * Get current datetime formatted
     *
     * @return string
     */
    public function time()
    {
        return now()->translatedFormat('l, d F Y H:i:s');
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
     * Get store phone
     *
     * @return string
     */
    public function phone()
    {
        return $this->store->phone ?? 'Phone not available';
    }

    /**
     * Get store email
     *
     * @return string
     */
    public function email()
    {
        return $this->store->email ?? 'Email not available';
    }

    /**
     * Get store website
     *
     * @return string
     */
    public function website()
    {
        return url('s/'.$this->store->id) ?? 'Website not available';
    }

    /**
     * Get store open hours
     *
     * @return string
     */
    public function hours()
    {
        return $this->store->hours ?? 'Hours not available';
    }

    /**
     * Get store full contact information formatted for WhatsApp
     *
     * @return string
     */
    public function contact()
    {
        $result = "*{$this->name()}*\n\n";

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

        if (!empty($this->store->hours)) {
            $result .= "\nStore hours: {$this->store->hours}";
        }

        return $result;
    }

    /**
     * Get store full business hours information
     *
     * @return string
     */
    public function businessHours()
    {
        // You can customize this based on your store model structure
        if (!empty($this->store->business_hours) && is_array($this->store->business_hours)) {
            $result = "*Business Hours*\n\n";

            foreach ($this->store->business_hours as $day => $hours) {
                $result .= "{$day}: {$hours}\n";
            }

            return $result;
        }

        return $this->hours();
    }

    /**
     * Get store description
     *
     * @return string
     */
    public function description()
    {
        return $this->store->description ?? 'No description available';
    }

    /**
     * Get store full information formatted for WhatsApp
     *
     * @return string
     */
    public function info()
    {
        $result = "*{$this->name()}*\n";
        $result .= "꘎━━━━━꘎\n";
        $result .= "࿐ " . $this->time() . " | " . $this->date() . "\n";
        $result .= "꘎━━━━━꘎\n\n";

        if (!empty($this->store->description)) {
            $result .= "{$this->store->description}\n\n";
        }

        $result .= $this->contact();

        return $result;
    }
}
