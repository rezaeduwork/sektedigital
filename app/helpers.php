<?php

function profile($user)
{
  if (!$user->photo) {
    return url('assets/images/avatar.jpg');
  }
  return url('storage/' . $user->photo);
}
function storeProfile($store)
{
  if (!$store->photo) {
    return url('assets/images/avatar.jpg');
  }
  return url('storage/' . $store->photo);
}
function productActivity($product, $activity, $by)
{
  if ($activity == 'view') {
    $todayView = \App\Models\ProductLog::whereProduct_id($product->id)->whereActivity($activity)->whereBy($by)->whereDate('created_at', \Carbon\Carbon::now())->first();
    if (!$todayView) {
      \App\Models\ProductLog::create([
        'activity' => $activity,
        'by' => $by,
        'product_id' => $product->id
      ]);
    }
  } else {
    \App\Models\ProductLog::create([
      'activity' => $activity,
      'by' => $by,
      'product_id' => $product->id
    ]);
  }
}
function transactionActivity($tx, $by, $activity, $description, $type = null, $target = null)
{
  $tx->logs()->create([
    'activity' => $activity,
    'description' => $description,
    'by' => $by,
    'target' => $target,
    'type' => ($type == null ? 'user' : $type)
  ]);
}
function productImage($image)
{
  return url('storage/' . $image->name);
}
function productInstantImage($product)
{
  if ($product->image) {
    return url($product->image);
  }
  if (\App\Models\ProductInstant::getBrandLogo($product->brand)) {
    return url(\App\Models\ProductInstant::getBrandLogo($product->brand));
  }
  return url('assets/images/product-instant.png');
}
function categoryImage($category)
{
  return url('storage/' . $category->icon);
}
function totalTransaction($availableCarts = null)
{
  if (!$availableCarts) {
    $availableCarts = auth()->user()->carts()->whereIn('id', session('selectedCarts'))->get();
  }
  $subTotal = $availableCarts->reduce(function ($carry, $item) {
    return $carry + ($item->product->price * $item->quantity);
  }, 0);
  return $subTotal;
}

function queryListUserTransaction($tab = null)
{
  $list = auth()->user()->transactions()->whereHas('payment', function ($query) {
    $query->whereStatus('settlement');
  });
  if ($tab) {
    $list->whereIn('status', [$tab]);
  }
  return $list;
}

function userActivity($activity, $description = null)
{
  auth()->user()->activities()->create([
    'activity' => $activity,
    'description' => $description
  ]);
}

function updateUserBalance($user) {}
function tripay()
{
  return (new \App\Services\Tripay);
}
function digiflazz()
{
  return (new \App\Services\Digiflazz);
}
function checkPayment($payment)
{
  return tripay()->checkTransactionDetail($payment->data['reference'])['data']['data'];
}
function storeTransactionQuery($status)
{
  return \App\Models\Transaction::query()->whereStore_id(auth()->user()->store->id)->whereStatus($status);
}
function storeTransactionDetailQuery($status)
{
  return \App\Models\TransactionDetail::query()->whereHas('transaction', function ($query) use ($status) {
    $query->whereStore_id(auth()->user()->store->id)->whereStatus($status);
  });
}
function platformFee($selectedPayment, $productFee)
{
  $feeData = tripay()->calculateFee($selectedPayment, $productFee)['data'];
  $feeMerchant = $feeData[0]['total_fee']['merchant'];
  $platformFee = ceil($feeMerchant);
  if ($selectedPayment === 'QRIS2' || $selectedPayment === 'QRIS') {
    $platformFee = $platformFee + (($productFee * config('services.platform.fee')) / 100);
  } else {
    $platformFee = $platformFee + (($productFee * config('services.platform.fee')) / 100);
  }
  return $platformFee;
}
function getRandomGuestDetail()
{
  $randomUID = uniqid() . time();
  $name = 'User ' . $randomUID;
  $phone = '62895355094422';
  $email = 'bitneetuser_' . $randomUID . '@gmail.com';
  if (auth()->check()) {
    $name = auth()->user()->name;
    $email = auth()->user()->email;
    $phone = auth()->user()->phone;
  } else if (session('username')) {
    $name = session('username');
    $email = session('useremail');
    $phone = session('userphone');
  } else {
    session()->put('username', $name);
    session()->put('useremail', $email);
    session()->put('userphone', $phone);
  }
  return [
    'customer_name' => $name,
    'customer_email' => $email,
    'customer_phone' => $phone,
  ];
}
function getTransactionInstantInformations($category, $brand, $values = [])
{
  $informations = [];
  if (in_array($category, ['Games'])) {
    $informations = [
      [
        'label' => 'Account ID',
        'name' => 'account_id',
        'type' => 'number',
        'value' => isset($values['account_id']) ? $values['account_id'] : null,
      ],
    ];
    if (strtolower($brand) == 'mobile legends') {
      $informations[] = [
        'label' => 'Zone ID',
        'name' => 'zone_id',
        'type' => 'number',
        'value' => isset($values['zone_id']) ? $values['zone_id'] : null,
      ];
    }
    $informations[] = [
      'label' => 'Email',
      'name' => 'email',
      'type' => 'text',
      'required' => false,
      'value' => isset($values['email']) ? $values['email'] : null,
    ];
    $informations[] = [
      'label' => 'Nomor HP',
      'name' => 'phone',
      'type' => 'number',
      'value' => isset($values['phone']) ? $values['phone'] : null,
      'required' => false
    ];
  } else if (in_array($category, ['Pulsa', 'Data'])) {
    $informations = [
      [
        'label' => 'Nomor HP',
        'name' => 'phone',
        'type' => 'number',
        'value' => isset($values['phone']) ? $values['phone'] : null,
      ],
    ];
  }

  return $informations;
}
function successPayment($invoice)
{
  if ($invoice->status !== 'pending') {
    return [
      'success' => true
    ];
  }
  // DOUBLE CHECK TX
  $checkTx = tripay()->checkTransactionDetail($invoice->data['reference']);
  if ($checkTx['status'] === true) {
    $invoice->update(['status' => 'settlement', 'settlement_at' => now()]);

    if ($invoice->transaction_type == 'basic') {
      $invoice->transactions()->where('status', 'unprocessed')->update(['status' => 'confirmed']);
      foreach ($invoice->transactions()->where('status', 'confirmed')->get() as $tx) {
        transactionActivity($tx, $tx->user_id, 'confirmed', ('transaction confirmed'));
      }
    } else {
      if ($invoice->singleTransaction->product->provider == 'digiflazz') {
        $data = digiflazz()->createTransaction($invoice->singleTransaction);
        if ($data['success'] === true) {
          $invoice->singleTransaction()->where('status', 'unprocessed')->update(['status' => 'finished']);
        } else {
          if ($data['data']['status'] == 'Pending') {
            $invoice->singleTransaction()->where('status', 'unprocessed')->update(['status' => 'confirmed']);
          } else {
            $invoice->singleTransaction()->where('status', 'unprocessed')->update(['status' => 'rejected']);
          }
        }
      }
    }

    if ($invoice->user) {
      $invoice->user->notify(new \App\Notifications\PaymentConfirmed($invoice, 'settlement'));
    }
  } else {
    return response()->json([
      'success' => false,
      'message' => $checkTx['data'],
    ]);
  }
  return response()->json([
    'success' => true
  ]);
}
function expirePayment($invoice)
{
  if ($invoice->status !== 'pending') {
    return [
      'success' => true
    ];
  }
  $invoice->update(['status' => 'expired']);
  if ($invoice->transaction_type == 'basic') {
    $invoice->transactions()->where('status', 'unprocessed')->update(['status' => 'expired']);
    foreach ($invoice->transactions as $tx) {
      transactionActivity($tx, $tx->user_id, 'expired', ('transaction expired'));
    }
  } else {
    if ($invoice->singleTransaction->product->provider == 'digiflazz') {
      $invoice->singleTransaction()->where('status', 'unprocessed')->update(['status' => 'expired']);
    }
  }
  $invoice->user->notify(new \App\Notifications\PaymentConfirmed($invoice, 'expired'));
  return [
    'success' => true
  ];
}
