<?php

namespace App\Services\Whatsapp\MessageParser;

use App\Models\Store;

class Formatter
{
    protected $store;

    /**
     * Constructor
     *
     * @param Store $store The store
     */
    public function __construct($store)
    {
        $this->store = $store;
    }

    /**
     * Format currency values
     *
     * @param mixed $amount The amount to format
     * @return string
     */
    public function currency($amount)
    {
        if (!is_numeric($amount)) {
            return "0";
        }

        return "Rp " . number_format((float)$amount, 0, ',', '.');
    }

    /**
     * Format date values
     *
     * @param string $date The date to format
     * @param string $format Optional format (default: d M Y)
     * @return string
     */
    public function date($date, $format = 'd M Y')
    {
        if (empty($date)) {
            return date($format);
        }

        try {
            return date($format, strtotime($date));
        } catch (\Exception $e) {
            return date($format);
        }
    }

    /**
     * Convert a string to uppercase
     *
     * @param string $text The text to format
     * @return string
     */
    public function upper($text)
    {
        return strtoupper($text);
    }

    /**
     * Convert a string to lowercase
     *
     * @param string $text The text to format
     * @return string
     */
    public function lower($text)
    {
        return strtolower($text);
    }

    /**
     * Format a string to title case
     *
     * @param string $text The text to format
     * @return string
     */
    public function title($text)
    {
        return ucwords(strtolower($text));
    }
}
