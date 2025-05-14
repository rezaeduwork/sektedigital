<?php

namespace App\Livewire\Store\Ppob;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ProductInstant;
use App\Models\StoreProductInstant;
use App\Models\Payment;
use App\Services\Tripay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AddProduct extends Component
{
  use WithPagination;

  public $search = '';
  public $category = '';
  public $brand = '';
  public $sortField = 'title';
  public $sortDirection = 'asc';
  public $selectedProduct = null;
  public $sellingPrice = 0;
  public $quantity = 1;

  // Payment related properties
  public $showPaymentModal = false;
  public $paymentMethod = '';
  public $paymentMethods = [];
  public $paymentReference = null;
  public $paymentAmount = 0;
  public $paymentStatus = null;
  public $paymentId = null;
  public $paymentInstructions = null;
  public $paymentExpiry = null;

  public function updatingSearch()
  {
    $this->resetPage();
  }

  public function updatingCategory()
  {
    $this->resetPage();
  }

  public function updatingBrand()
  {
    $this->resetPage();
  }

  public function sortBy($field)
  {
    if ($this->sortField === $field) {
      $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
      $this->sortField = $field;
      $this->sortDirection = 'asc';
    }
  }

  public function selectProduct($id)
  {
    $this->selectedProduct = ProductInstant::find($id);
    $this->sellingPrice = $this->selectedProduct->price;
    $this->loadPaymentMethods();
  }

  public function loadPaymentMethods()
  {
    $tripay = tripay();
    $channels = $tripay->getPaymentChannels();

    if ($channels && isset($channels['data'])) {
      // Filter for common payment methods - can be customized based on preference
      $this->paymentMethods = collect($channels['data'])
        ->filter(function ($method) {
          return in_array($method['group'], ['Virtual Account', 'QRIS', 'QRIS2']) ||
            in_array($method['code'], ['QRIS', 'QRIS2']);
        })
        ->toArray();
    }
  }

  public function initPayment()
  {
    $this->validate([
      'sellingPrice' => 'required|numeric|min:' . $this->selectedProduct->price,
      'quantity' => 'required|integer|min:1',
      'paymentMethod' => 'required',
    ], [
      'sellingPrice.min' => 'Selling price must be at least the product price.',
      'paymentMethod.required' => 'Please select a payment method.'
    ]);
    $user = Auth::user();
    $totalAmount = $this->getSubtotalProperty(); // Use the calculated subtotal

    // Create unique reference
    $merchantRef = 'PPOB-STOCK-' . Str::random(8);

    // Create order item for Tripay
    $orderItems = [
      [
        'name' => $this->selectedProduct->title,
        'price' => $this->selectedProduct->price,
        'quantity' => $this->quantity,
      ]
    ];

    // Prepare transaction data
    $transactionData = [
      'method' => $this->paymentMethod,
      'merchant_ref' => $merchantRef,
      'amount' => $totalAmount,
      'customer_name' => $user->name,
      'customer_email' => $user->email,
      'customer_phone' => $user->phone ?? '08123456789',
      'order_items' => $orderItems,
      'return_url' => route('store.ppob.add'),
      'expired_time' => (time() + (24 * 60 * 60)), // 24 hours
    ];

    // Call Tripay service to create transaction
    $tripay = new Tripay();
    $transaction = $tripay->createTransaction($transactionData);

    if ($transaction['status']) {
      // Format payment data for webhook processing
      $paymentData = [
        'product_id' => $this->selectedProduct->id,
        'quantity' => $this->quantity,
        'selling_price' => $this->sellingPrice,
        'transaction_data' => $transaction['data'],
        'merchant_ref' => $merchantRef,
      ];

      // Store payment information in database
      $payment = Payment::create([
        'status' => 'pending',
        'token' => $transaction['data']['data']['reference'],
        'amount' => $totalAmount,
        'user_id' => $user->id,
        'data' => json_encode($paymentData),
        'transaction_type' => 'instant',
        'expired_at' => now()->addHours(24)
      ]);

      $this->paymentId = $payment->id;
      $this->paymentReference = $transaction['data']['data']['reference'];
      $this->paymentAmount = $totalAmount;
      $this->paymentInstructions = $transaction['data']['data']['instructions'];
      $this->paymentExpiry = $transaction['data']['data']['expired_time'];
      $this->paymentStatus = 'pending';

      session()->flash('payment-message', 'Payment has been initiated. Please complete your payment.');
    } else {
      session()->flash('payment-error', 'Failed to initiate payment: ' . $transaction['data']);
    }
  }

  public function checkPaymentStatus()
  {
    if (!$this->paymentReference) {
      return;
    }

    $tripay = new Tripay();
    $response = $tripay->checkTransactionDetail($this->paymentReference);

    if ($response['status']) {
      $status = $response['data']['data']['status'];

      if ($status === 'PAID') {
        // Update payment in database
        $payment = Payment::where('token', $this->paymentReference)->first();
        if ($payment) {
          $payment->update([
            'status' => 'settlement',
            'settlement_at' => now(),
          ]);

          $this->paymentStatus = 'paid';
          $this->saveProduct($payment);
        }
      } else if (in_array($status, ['EXPIRED', 'FAILED', 'CANCELLED'])) {
        $payment = Payment::where('token', $this->paymentReference)->first();
        if ($payment) {
          $payment->update([
            'status' => strtolower($status),
          ]);

          $this->paymentStatus = strtolower($status);
          session()->flash('payment-error', 'Payment ' . strtolower($status));
        }
      }

      return $status;
    }

    return null;
  }

  public function saveProduct($payment = null)
  {
    if (!$payment) {
      return;
    }

    // Payment data contains all the information about the product
    $paymentData = json_decode($payment->data, true);

    if (!isset($paymentData['product_id']) || !isset($paymentData['quantity']) || !isset($paymentData['selling_price'])) {
      session()->flash('error', 'Invalid payment data');
      return;
    }

    $store = Auth::user()->store;
    $productId = $paymentData['product_id'];
    $quantity = $paymentData['quantity'];
    $sellingPrice = $paymentData['selling_price'];

    $selectedProduct = ProductInstant::find($productId);
    if (!$selectedProduct) {
      session()->flash('error', 'Product not found');
      return;
    }

    // Check if product already exists for this store
    $existingProduct = StoreProductInstant::where('store_id', $store->id)
      ->where('product_instant_id', $selectedProduct->id)
      ->first();

    try {
      if ($existingProduct) {
        // Update existing product
        $existingProduct->update([
          'selling_price' => $sellingPrice,
          'stock' => $existingProduct->stock + $quantity,
        ]);

        // The success message will be shown after the payment modal is closed
        session()->flash('message', 'Payment successful! Product stock has been updated.');
      } else {
        // Create new product
        StoreProductInstant::create([
          'store_id' => $store->id,
          'product_instant_id' => $selectedProduct->id,
          'code' => $selectedProduct->code,
          'provider' => $selectedProduct->provider,
          'brand' => $selectedProduct->brand,
          'category' => $selectedProduct->category,
          'title' => $selectedProduct->title,
          'highlight' => $selectedProduct->highlight,
          'description' => $selectedProduct->description,
          'price' => $selectedProduct->price,
          'selling_price' => $sellingPrice,
          'slug' => $selectedProduct->slug,
          'stock' => $quantity,
          'provider_stock' => $selectedProduct->provider_stock,
          'status' => 'active',
          'provider_status' => $selectedProduct->provider_status,
          'image' => $selectedProduct->image,
          'type' => $selectedProduct->type,
        ]);

        session()->flash('message', 'Payment successful! New product added to your store.');
      }

      // Close the payment modal after successful saving
      $this->reset([
        'selectedProduct',
        'sellingPrice',
        'quantity',
        'paymentMethod',
        'paymentReference',
        'paymentAmount',
        'paymentStatus',
        'paymentId',
        'paymentInstructions',
        'paymentExpiry'
      ]);
    } catch (\Exception $e) {
      session()->flash('payment-error', 'Error updating product stock: ' . $e->getMessage());
    }
  }

  public function cancelSelection()
  {
    $this->reset([
      'selectedProduct',
      'sellingPrice',
      'quantity',
      'paymentMethod',
      'paymentReference',
      'paymentAmount',
      'paymentStatus',
      'paymentId',
      'paymentInstructions',
      'paymentExpiry'
    ]);
  }

  public function updatedQuantity()
  {
    // Recalculate the payment amount when quantity changes
    if ($this->selectedProduct) {
      $this->paymentAmount = $this->selectedProduct->price * $this->quantity;
    }
  }

  public function getSubtotalProperty()
  {
    return $this->selectedProduct ? $this->selectedProduct->price * $this->quantity : 0;
  }

  public function render()
  {
    // If payment is being processed, check status
    if ($this->paymentReference && $this->paymentStatus == 'pending') {
      $this->checkPaymentStatus();
    }

    $query = ProductInstant::query()->where('status', 'active');

    if (!empty($this->search)) {
      $query->where(function ($q) {
        $q->where('title', 'like', '%' . $this->search . '%')
          ->orWhere('code', 'like', '%' . $this->search . '%')
          ->orWhere('description', 'like', '%' . $this->search . '%');
      });
    }

    if (!empty($this->category)) {
      $query->where('category', $this->category);
    }

    if (!empty($this->brand)) {
      $query->where('brand', $this->brand);
    }

    $products = $query->orderBy($this->sortField, $this->sortDirection)
      ->paginate(10);

    $categories = ProductInstant::where('status', 'active')
      ->distinct()
      ->pluck('category')
      ->filter()
      ->toArray();

    $brands = ProductInstant::where('status', 'active')
      ->when(!empty($this->category), function ($q) {
        return $q->where('category', $this->category);
      })
      ->distinct()
      ->pluck('brand')
      ->filter()
      ->toArray();

    // Define the route for the add product page
    if (!app()->routesAreCached()) {
      \Illuminate\Support\Facades\Route::get('/store/ppob/add', static::class)->name('store.ppob.add');
    }

    return view('livewire.store.ppob.add-product', [
      'products' => $products,
      'categories' => $categories,
      'brands' => $brands
    ])->layout('components.layouts.app-dashboard');
  }
}
