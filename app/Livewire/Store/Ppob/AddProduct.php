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

  // Add properties to store product prices
  public $productPrices = [];

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

    // Check if product already exists for this store
    $store = Auth::user()->store;
    $existingProduct = StoreProductInstant::where('store_id', $store->id)
      ->where('product_instant_id', $this->selectedProduct->id)
      ->first();

    if ($existingProduct) {
      $this->dispatch('alert-error', message: 'Produk ini sudah tersedia di toko Anda.');
      $this->selectedProduct = null;
      return;
    }
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

    // First create a payment record in the database
    $payment = Payment::create([
      'status' => 'pending',
      'amount' => $totalAmount,
      'user_id' => $user->id,
      'transaction_type' => 'seller_ppob',
      'expired_at' => now()->addHours(24)
    ]);

    // Create unique reference using the payment ID
    $merchantRef = $payment->id;

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

      // Update the payment record with transaction details
      $payment->update([
        'token' => $transaction['data']['data']['reference'],
        'data' => json_encode($paymentData),
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
      session()->flash('payment-error', 'Payment not found.');
      return;
    }
    $payment = Payment::find($payment->id);

    try {
      if ($payment->status == 'settlement') {
        session()->flash('message', 'Pembayaran berhasil!');
      } else {
        session()->flash('payment-error', 'Pembayaran tidak berhasil silahkan hubungi admin.');
        return;
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

  // Method to add product directly to store without payment
  public function addToStore($productId)
  {
    $productInstant = ProductInstant::find($productId);
    if (!$productInstant) {
      $this->dispatch('alert-error', message: 'Produk tidak ditemukan.');
      return;
    }

    $store = Auth::user()->store;

    // Check if selling price is set
    $sellingPrice = $this->productPrices[$productId] ?? null;
    if (!$sellingPrice || $sellingPrice < $productInstant->price) {
      $this->dispatch('alert-error', message: 'Harga jual harus diisi dan minimal sama dengan harga dasar.');
      return;
    }

    // Check if product already exists in store
    $existingProduct = StoreProductInstant::where('store_id', $store->id)
      ->where('product_instant_id', $productInstant->id)
      ->first();

    if ($existingProduct) {
      $this->dispatch('alert-error', message: 'Produk ini sudah tersedia di toko Anda.');
      return;
    }

    try {
      // Create new product in store
      StoreProductInstant::create([
        'store_id' => $store->id,
        'product_instant_id' => $productInstant->id,
        'code' => $productInstant->code,
        'provider' => $productInstant->provider,
        'brand' => $productInstant->brand,
        'category' => $productInstant->category,
        'title' => $productInstant->title,
        'highlight' => $productInstant->highlight,
        'description' => $productInstant->description,
        'price' => $productInstant->price,
        'selling_price' => $sellingPrice,
        'slug' => $productInstant->slug,
        'image' => $productInstant->image,
        'type' => $productInstant->type
      ]);

      $this->dispatch('alert-success', message: 'Produk berhasil ditambahkan ke toko Anda.');
    } catch (\Exception $e) {
      $this->dispatch('alert-error', message: 'Gagal menambahkan produk: ' . $e->getMessage());
    }
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
