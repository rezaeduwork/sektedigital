<?php

namespace App\Services\Whatsapp\MessageParser;

use App\Models\Store;
use App\Models\WhatsappCustomerSession;
use App\Models\WhatsappBotTemplate;
use Illuminate\Support\Facades\Log;

class Template
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
     * Render a template by its code
     *
     * @param array $data The data containing the template code
     * @return string
     */
    public function render($data)
    {
        if (!isset($data['query'])) {
            return "Template code is required.";
        }

        $templateCode = $data['query'];

        // Try to find the template for this store
        $template = WhatsappBotTemplate::where('store_id', $this->store->id)
            ->where('code', $templateCode)
            ->where('is_active', true)
            ->first();

        // If not found, try to find a master template
        if (!$template) {
            $template = WhatsappBotTemplate::where('code', $templateCode)
                ->where('is_master', true)
                ->where('is_active', true)
                ->first();
        }

        if (!$template) {
            return "Template '{$templateCode}' not found.";
        }

        // Return the template content (no recursive parsing for now to avoid loops)
        return $template->content;
    }

    /**
     * Get a list of available templates
     *
     * @return string
     */
    public function list()
    {
        // Get templates specific to this store plus master templates
        $templates = WhatsappBotTemplate::where(function($query) {
                $query->where('store_id', $this->store->id)
                    ->orWhere('is_master', true);
            })
            ->where('is_active', true)
            ->get();

        if ($templates->isEmpty()) {
            return "No templates available.";
        }

        $result = "*Available Templates:*\n\n";

        foreach ($templates as $template) {
            $result .= "- *{$template->code}*: {$template->name}\n";
        }

        return $result;
    }

    /**
     * Get welcome message template
     *
     * @return string
     */
    public function welcome()
    {
        // Try to find a welcome template for this store
        $template = WhatsappBotTemplate::where('store_id', $this->store->id)
            ->where('code', 'welcome')
            ->where('is_active', true)
            ->first();

        // If not found, try to find a master welcome template
        if (!$template) {
            $template = WhatsappBotTemplate::where('code', 'welcome')
                ->where('is_master', true)
                ->where('is_active', true)
                ->first();
        }

        if (!$template) {
            return "Welcome to *{$this->store->name}*! How can we help you today?";
        }

        // Return the template content
        return $template->content;
    }
}
