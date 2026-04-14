<?php

namespace App\Livewire\Admin\PaymentGateway;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\PaymentGateway as PaymentGatewayModel;

class Update extends Component
{
  public $gateway;
  public $gatewayId;
  public $status;

  // Common fields
  public $display_name;
  public $description;

  // Dynamic data fields
  public $api_key = '';
  public $api_id = '';
  public $private_key = '';
  public $secret_key = '';
  public $merchant_code = '';
  public $merchant_id = '';
  public $callback_token = '';
  public $environment = 'sandbox';

  public function mount($id)
  {
    $this->gateway = PaymentGatewayModel::findOrFail($id);
    $this->gatewayId = $this->gateway->id;
    $this->display_name = $this->gateway->display_name;
    $this->description = $this->gateway->description;
    $this->status = $this->gateway->status;

    // Load data fields
    $data = $this->gateway->data ?? [];
    $this->api_key = $data['api_key'] ?? '';
    $this->api_id = $data['api_id'] ?? '';
    $this->private_key = $data['private_key'] ?? '';
    $this->secret_key = $data['secret_key'] ?? '';
    $this->merchant_code = $data['merchant_code'] ?? '';
    $this->merchant_id = $data['merchant_id'] ?? '';
    $this->callback_token = $data['callback_token'] ?? '';
    $this->environment = $data['environment'] ?? 'sandbox';
  }

  public function toggleStatus()
  {
    $this->status = $this->status === 'active' ? 'inactive' : 'active';
  }

  public function update()
  {
    $this->validate([
      'display_name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'status' => 'required|in:active,inactive',
    ]);
    // Build data array based on gateway type
    $data = [
      'environment' => $this->environment,
    ];

    switch ($this->gateway->name) {
      case 'tripay':
        $data['api_key'] = $this->api_key;
        $data['private_key'] = $this->private_key;
        $data['merchant_code'] = $this->merchant_code;
        break;

      case 'xendit':
        $data['api_key'] = $this->api_key;
        $data['callback_token'] = $this->callback_token;
        break;

      case 'sakurupiah':
        $data['api_id'] = $this->api_id;
        $data['api_key'] = $this->api_key;
        break;

      case 'paymenku':
        $data['api_key'] = $this->api_key;
        break;
    }

    $this->gateway->update([
      'display_name' => $this->display_name,
      'description' => $this->description,
      'status' => $this->status,
      'data' => $data,
    ]);

    $this->dispatch('reload')->to(\App\Livewire\Admin\PaymentGateway::class);
    $this->dispatch('alert-success', ['message' => 'Payment gateway updated successfully']);
  }

  public function render()
  {
    return view('livewire.admin.payment-gateway.update');
  }
}
