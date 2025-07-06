<?php

namespace App\Livewire\Store\Ppob;

use Livewire\Component;
use App\Models\StoreProductInstant;
use App\Models\StoreTransactionInstant;
use App\Services\Digiflazz;
use Illuminate\Support\Facades\Auth;

class CreateTransaction extends Component
{
  public $productId;
  public $product;
  public $customerNo = '';
  public $loading = false;
  public $formFields = [];

  public function mount($productId)
  {
    $this->productId = $productId;
    $this->loadProduct();
  }

  public function loadProduct()
  {
    $store = Auth::user()->store;
    $this->product = StoreProductInstant::where('store_id', $store->id)
      ->where('id', $this->productId)
      ->first();

    if (!$this->product) {
      return redirect()->route('store.ppob.manage')->with('error', 'Product not found.');
    }

    // Initialize form fields based on product category
    $this->setFormFields();
  }

  protected function setFormFields()
  {
    // Reset form fields
    $this->formFields = [];

    // Set default customer_no field
    $this->formFields[] = [
      'name' => 'customer_no',
      'label' => 'Customer Number',
      'type' => 'text',
      'required' => true,
      'value' => ''
    ];

    // Add additional fields based on product category and brand
    if (strtolower($this->product->category) === 'games') {
      if (strtolower($this->product->brand) === 'mobile legends') {
        $this->formFields = [
          [
            'name' => 'account_id',
            'label' => 'Account ID',
            'type' => 'text',
            'required' => true,
            'value' => ''
          ],
          [
            'name' => 'zone_id',
            'label' => 'Zone ID',
            'type' => 'text',
            'required' => true,
            'value' => ''
          ]
        ];
      } else {
        $this->formFields = [
          [
            'name' => 'account_id',
            'label' => 'Account ID',
            'type' => 'text',
            'required' => true,
            'value' => ''
          ]
        ];
      }
    }
  }

  public function processTransaction()
  {
    // Validate form fields
    $rules = [];
    foreach ($this->formFields as $field) {
      $rules['formFields.' . array_search($field, $this->formFields) . '.value'] = 'required';
    }

    $this->validate($rules, [
      'formFields.*.value.required' => 'This field is required.'
    ]);

    $this->loading = true;

    $store = Auth::user()->store;

    // Check master product stock in the ProductInstant model
    $masterProduct = $this->product->productInstant;
    if (!$masterProduct) {
      session()->flash('error', 'Master product not found.');
      $this->loading = false;
      return;
    }

    // Check if the master product stock is available (if not unlimited)
    if ($masterProduct->stock !== -1 && $masterProduct->stock <= 0) {
      session()->flash('error', 'Product is out of stock.');
      $this->loading = false;
      return;
    }

    try {
      // Create transaction record
      $transaction = StoreTransactionInstant::create([
        'code' => StoreTransactionInstant::generateCode(),
        'store_id' => $store->id,
        'store_product_instant_id' => $this->product->id,
        'provider' => $this->product->provider,
        'customer_no' => $this->formFields[0]['value'], // Assuming first field is customer number
        'price' => $this->product->price,
        'fee' => 0, // No fee for now
        'total' => $this->product->selling_price,
        'status' => 'pending',
        'data' => $this->formFields // Store form data for reference
      ]);

      // Prepare data for Digiflazz service
      $txData = (object) [
        'id' => $transaction->code,
        'product' => (object) [
          'code' => $this->product->code,
          'category' => $this->product->category,
          'brand' => $this->product->brand
        ],
        'data' => $this->formFields
      ];

      // Call Digiflazz service
      $digiflazz = new Digiflazz();
      $response = $digiflazz->createTransaction($txData);

      // Update transaction
      $transaction->response_data = $response;
      $transaction->processed_at = now();

      if ($response['success']) {
        $transaction->status = 'success';
        $transaction->completed_at = now();

        // Update master stock if not unlimited
        if ($masterProduct->stock !== -1) {
          $masterProduct->decrement('stock');
        }

        session()->flash('message', 'Transaction processed successfully.');
      } else {
        $transaction->status = 'failed';
        session()->flash('error', 'Transaction failed. Please try again.');
      }

      $transaction->save();
    } catch (\Exception $e) {
      session()->flash('error', 'An error occurred: ' . $e->getMessage());
    }

    $this->loading = false;
    $this->emit('refreshProducts');
  }

  public function render()
  {
    return view('livewire.store.ppob.create-transaction')->layout('components.layouts.app-dashboard');
  }
}
