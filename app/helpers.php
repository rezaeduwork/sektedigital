<?php

function profile($user) {
  if (!$user->photo) {
    return url('assets/images/avatar.jpg');
  }
  return url('storage/'.$user->photo);
}
function storeProfile($store) {
  if (!$store->photo) {
    return url('assets/images/avatar.jpg');
  }
  return url('storage/'.$store->photo);
}
function productActivity($activity, $by) {
  if ($activity == 'view') {
    $todayView = \App\Models\ProductLog::whereActivity($activity)->whereBy($by)->whereDate('created_at', \Carbon\Carbon::now())->first();
    if (!$todayView) {
      \App\Models\ProductLog::create([
        'activity' => $activity,
        'by' => $by
      ]);
    }
  } else {
    \App\Models\ProductLog::create([
      'activity' => $activity,
      'by' => $by
    ]);
  }
}
function transactionActivity($tx,$by,$activity,$description) {
  $tx->logs()->create([
    'activity' => $activity,
    'description' => $description,
    'by' => $by
  ]);
}
function productImage($image) {
  return url('storage/'.$image->name);
}
function categoryImage($category) {
  return url('storage/'.$category->icon);
}
function totalTransaction($availableCarts = null) {
  if (!$availableCarts) {
    $availableCarts = auth()->user()->carts()->whereIn('id', session('selectedCarts'))->get();
  }
  $subTotal = $availableCarts->reduce(function ($carry, $item) {
    return $carry + ($item->product->price * $item->quantity);
  }, 0);
  return $subTotal;
}

function queryListUserTransaction($tab = 'Semua') {
  $list = auth()->user()->transactions();
  if ($tab == 'Belum Bayar') {
    $list->whereIn('status', ['unprocessed']);
  } else if ($tab == 'Menunggu Konfirmasi') {
    $list->whereIn('status', ['confirmed']);
  }else if ($tab == 'Proses') {
    $list->whereIn('status', ['accepted']);
  } else if ($tab == 'Selesai') {
    $list->whereIn('status', ['finished']);
  } else if ($tab == 'Dibatalkan') {
    $list->whereIn('status', ['rejected','cancelled']);
  }
  return $list;
}

function userActivity($activity, $description = null) {
  auth()->user()->activities()->create([
    'activity' => $activity,
    'description' => $description
  ]);
}
