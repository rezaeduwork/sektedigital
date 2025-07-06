<?php

namespace App\Services\Whatsapp\MessageParser;

use App\Models\Product as ProductModel;
use App\Models\Store;

class Product
{
    protected $store;

    /**
     * Constructor
     *
     * @param Store $store The store to retrieve products from
     */
    public function __construct($store)
    {
        $this->store = $store;
    }

    /**
     * Get all products formatted for WhatsApp display
     *
     * @return string
     */
    public function all()
    {
        // Only show products for this store
        $products = ProductModel::where('status', 'active')
            ->where('store_id', $this->store->id)
            ->take(config('app.env') === 'production' ? 100 : 10)
            ->get();

        if ($products->isEmpty()) {
            return "No products available at the moment.";
        }

        $result = "";
        foreach ($products as $product) {
            $result .= "*{$product->title}* \n";
            $result .= "Price: " . number_format($product->price, 0, ',', '.') . "\n";
            $result .= "Stock: {$product->stock}\n";
            $result .= "⤷".url($product->slug)."\n\n";
        }

        $result .= "Menampilkan " . $products->count() . " dari ".ProductModel::where('store_id', $this->store->id)->count()." produk.";

        return $result;
    }

    /**
     * Search for products by query input or return data based on the parameter name
     *
     * @param array $data The data containing the parameter name to access from session context
     * @return string
     */
    public function input($data)
    {
        if (!isset($data['param'])) {
            return "";
        }

        // Get the session
        $session = request()->whatsappSession;
        if (!$session) {
            return "";
        }

        // Get the parameter value from session context data
        $paramName = $data['param'];
        $paramValue = $session->context_data[$paramName] ?? null;

        return $paramValue;
    }

    /**
     * Get a specific product
     *
     * @return string
     */
    public function find($productId = null)
    {
        $session = request()->whatsappSession;
        if (!$session || !isset($session->context_data['id'])) {
            return "Product ID is required.";
        }
        $productId = $session->context_data['id'];

        $product = ProductModel::where('store_id', $this->store->id)
            ->where('id', $productId)
            ->where('status', 'active')
            ->first();

        if (!$product) {
            return "Product with ID {$productId} not found.";
        }

        $result = "*{$product->title}*\n";
        $result .= "ID: {$product->id}\n";
        $result .= "Price: " . number_format($product->price, 0, ',', '.') . "\n";
        $result .= "Stock: {$product->stock}\n";

        if (!empty($product->description)) {
            $result .= "\n*Description:*\n{$product->description}\n";
        }

        return $result;
    }    /**
     * Search for products - used by the search command
     *
     * @return string
     */
    public function search()
    {
        // Get the query from the session context data
        $session = request()->whatsappSession;
        if (!$session || !isset($session->context_data['query'])) {
            return "Search query is missing.";
        }

        $query = $session->context_data['query'];

        // Only search products for this store
        $products = ProductModel::where('store_id', $this->store->id)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%");
            })
            ->where('status', 'active')
            ->get();

        if ($products->isEmpty()) {
            return "No products found matching '{$query}'.";
        }

        $result = "";
        foreach ($products as $product) {
            $result .= "*{$product->title}* \n";
            $result .= "Price: " . number_format($product->price, 0, ',', '.') . "\n";
            $result .= "Stock: {$product->stock}\n";
            $result .= "⤷".url($product->slug)."\n\n";
        }

        $result .= "Ditemukan " . $products->count() . " produk.";

        return $result;
    }
}
