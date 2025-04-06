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
