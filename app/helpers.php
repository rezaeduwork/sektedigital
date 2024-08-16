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
function productImage($image) {
  return url('storage/'.$image->name);
}
