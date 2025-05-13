<?php

namespace App\Livewire\Store;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Whatsapp extends Component
{
  public $store;
  public $whatsapp;
  public $qrCode;
  public $status;
  public $phoneNumber;
  public $isLoading = false;
  public $connectionError = null;
  public $errorTimestamp = null;

  public function mount()
  {
    // Get current user's store
    $this->store = auth()->user()->store;

    // Get or create store WhatsApp record
    $this->loadWhatsappData();
  }

  public function loadWhatsappData()
  {
    $this->whatsapp = $this->store->whatsapp;

    if (!$this->whatsapp) {
      // Create a new WhatsApp connection for this store
      $this->whatsapp = $this->store->whatsapp()->create([
        'status' => 'disconnected',
      ]);
    }

    $oldStatus = $this->status;
    $this->qrCode = $this->whatsapp->qr_code;
    $this->status = $this->whatsapp->status;
    $this->phoneNumber = $this->whatsapp->phone_number;

    // If status changed, dispatch an event
    if ($oldStatus !== $this->status) {
      $this->dispatch('statusUpdated', $this->status);
    }
  }

  public function connectWhatsapp()
  {
    $this->isLoading = true;
    $this->connectionError = null;

    try {
      // Format the connection ID as CID_{id} for the NodeJS gateway
      $formattedConnectionId = 'CID_' . $this->whatsapp->id;

      // Make API request to the NodeJS WhatsApp gateway using the formatted connectionId
      $response = Http::post(env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000') . '/api/connection', [
        'connectionId' => $formattedConnectionId
      ]);

      if ($response->successful()) {
        $this->status = 'connecting';
        $this->whatsapp->update(['status' => 'connecting']);
        $this->dispatch('statusUpdated', $this->status);
        $this->dispatch('initQrScanner');
      } else {
        // Get error message from response if available
        $errorMessage = $response->json('error') ?? $response->json('message') ?? 'Failed to initialize WhatsApp connection.';
        $this->connectionError = $errorMessage;
        $this->errorTimestamp = now()->timestamp;
      }
    } catch (\Exception $e) {
      $this->connectionError = 'Error connecting to WhatsApp gateway: ' . $e->getMessage();
      $this->errorTimestamp = now()->timestamp;
    }

    $this->isLoading = false;
  }

  public function disconnectWhatsapp()
  {
    $this->isLoading = true;

    try {
      // Format the connection ID as CID_{id} for the NodeJS gateway
      $formattedConnectionId = 'CID_' . $this->whatsapp->id;

      // Make API request to disconnect from the gateway using the formatted ID
      $response = Http::delete(env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000') . '/api/connection/' . $formattedConnectionId);

      if ($response->successful()) {
        $this->status = 'disconnected';
        $this->whatsapp->update(['status' => 'disconnected']);
        $this->qrCode = null;
        $this->dispatch('statusUpdated', $this->status);
      } else {
        session()->flash('error', 'Failed to disconnect WhatsApp connection.');
      }
    } catch (\Exception $e) {
      session()->flash('error', 'Error disconnecting from WhatsApp gateway: ' . $e->getMessage());
    }

    $this->isLoading = false;
  }
  public function refreshQrCode()
  {
    try {
      // Format the connection ID as CID_{id} for the NodeJS gateway
      $formattedConnectionId = 'CID_' . $this->whatsapp->id;

      // Only disconnect if we're not already connecting
      // This prevents the "User initiated disconnect" message when just refreshing a QR code
      if ($this->status !== 'connecting') {
        // First disconnect the current connection if we're connected
        $disconnectResponse = Http::delete(env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000') . '/api/connection/' . $formattedConnectionId);

        // Short delay to ensure disconnection is processed
        usleep(500000); // 500ms delay
      }

      // Reconnect to get a fresh QR code
      $response = Http::post(env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000') . '/api/connection', [
        'connectionId' => $formattedConnectionId,
        'refreshQr' => true  // Add a flag to indicate this is just a QR refresh
      ]);

      if ($response->successful()) {
        // Update status to connecting
        $this->status = 'connecting';
        $this->whatsapp->update(['status' => 'connecting']);
        $this->dispatch('statusUpdated', $this->status);

        // Reload data after a short delay to allow QR code to be generated
        $this->dispatch('initQrScanner');
      } else {
        $this->connectionError = 'Failed to refresh QR code: ' . ($response->json('message') ?? $response->body());
      }
    } catch (\Exception $e) {
      $this->connectionError = 'Error refreshing QR code: ' . $e->getMessage();
    }
  }

  public function getWhatsappStatusClass()
  {
    switch ($this->status) {
      case 'connected':
        return 'text-green-600';
      case 'connecting':
        return 'text-yellow-600';
      default:
        return 'text-red-600';
    }
  }

  public function clearConnectionError()
  {
    $this->connectionError = null;
    $this->errorTimestamp = null;
  }

  public function connectionTimeout()
  {
    if ($this->status === 'connecting') {
      if (!$this->qrCode) {
        // QR code was never received
        $this->connectionError = "Connection timeout. QR code not received from WhatsApp gateway. Please try again.";
      } else {
        // QR code was received but never scanned
        $this->connectionError = "Connection timeout. QR code was not scanned in time. Please try again.";
      }

      $this->status = 'disconnected';
      // Update the status in the database
      $this->whatsapp->update([
        'status' => 'disconnected',
        'qr_code' => null // Clear the QR code
      ]);

      // Dispatch status updated event to trigger UI updates and timer resets
      $this->dispatch('statusUpdated', 'disconnected');

      // Try to notify the gateway that we're disconnecting due to timeout
      try {
        $formattedConnectionId = 'CID_' . $this->whatsapp->id;
        Http::delete(env('WHATSAPP_GATEWAY_URL', 'http://localhost:3000') . '/api/connection/' . $formattedConnectionId . '/timeout');
      } catch (\Exception $e) {
        // Just log the error, don't show to user
        \Log::error('Failed to notify gateway of connection timeout: ' . $e->getMessage());
      }
    }
  }

  public function render()
  {
    // Get both store-specific and global commands
    $commands = \App\Models\WhatsappBotCommand::forStore($this->store->id)
      ->orderBy('display_order', 'asc')
      ->orderByRaw('store_id IS NULL ASC') // Show store-specific commands first
      ->paginate(10);
    return view('livewire.store.whatsapp', compact('commands'))->layout('components.layouts.app-dashboard');
  }

  /**
   * Handle successful QR code generation
   */
  public function qrCodeGenerated()
  {
    // If we got a QR code, update the status to ensure we're in connecting state
    if ($this->qrCode && $this->status !== 'connected') {
      $oldStatus = $this->status;
      $this->status = 'connecting';
      $this->whatsapp->update(['status' => 'connecting']);

      // If status changed, dispatch an event to trigger UI updates
      if ($oldStatus !== 'connecting') {
        $this->dispatch('statusUpdated', 'connecting');
      }
    }
  }

  protected function getListeners()
  {
    return [
      'connectionTimeout',
      'refreshQrCode',
      'initQrScanner',
      'qrCodeGenerated'
    ];
  }
}
