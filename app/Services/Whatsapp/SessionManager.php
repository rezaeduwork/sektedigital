<?php

namespace App\Services\Whatsapp;

use App\Models\WhatsappCustomerSession;

class SessionManager
{
  public function getOrCreateCustomerSession($store, $phone, $state = 'browsing')
  {
    // Generate a unique session ID if this is a new session
    $sessionId = 'WA_' . $store->id . '_' . preg_replace('/[^0-9]/', '', $phone) . '_' . time();
    \Log::debug("Fetch session", [
      'store_id' => $store->id,
      'phone_number' => $phone
    ]);
    // Fetch existing session or create a new one
    return WhatsappCustomerSession::firstOrCreate([
      'store_id' => $store->id,
      'phone_number' => $phone
    ], [
      'session_id' => $sessionId,
      'current_state' => $state,
      'context_data' => [],
      'cart_data' => ['items' => []],
      'last_interaction_at' => now(),
      'is_active' => true
    ]);
  }

  /**
   * Update customer session state and context data
   *
   * @param WhatsappCustomerSession $session The session to update
   * @param string $state The new state to set
   * @param array $contextData Additional context data to store
   * @return WhatsappCustomerSession
   */
  public function updateState(WhatsappCustomerSession $session, string $state = null, array $contextData = [])
  {
    $data = [
      'last_interaction_at' => now()
    ];

    if ($state !== null) {
      $data['current_state'] = $state;
    }

    if (!empty($contextData)) {
      // Merge new context data with existing data
      $existingContext = $session->context_data ?? [];
      $mergedContext = array_merge($existingContext, $contextData);
      $data['context_data'] = $mergedContext;
    }

    $session->update($data);

    return $session;
  }
}
