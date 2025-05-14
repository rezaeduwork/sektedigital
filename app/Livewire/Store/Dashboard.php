<?php

namespace App\Livewire\Store;

use App\Models\Transaction;
use App\Models\StoreTransactionInstant;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
  public function render()
  {
    $store = Auth::user()->store;

    // Get current month's date range
    $currentMonth = Carbon::now()->startOfMonth();
    $lastMonth = Carbon::now()->subMonth()->startOfMonth();

    // Calculate regular store revenue
    $currentMonthRevenue = Transaction::where('store_id', $store->id)
      ->whereMonth('created_at', Carbon::now()->month)
      ->whereYear('created_at', Carbon::now()->year)
      ->where('status', 'completed')
      ->sum('amount');

    // Calculate PPOB revenue
    $currentMonthPPOBRevenue = StoreTransactionInstant::where('store_id', $store->id)
      ->whereMonth('created_at', Carbon::now()->month)
      ->whereYear('created_at', Carbon::now()->year)
      ->where('status', 'success')
      ->sum('total');

    // Total revenue (regular + PPOB)
    $totalMonthRevenue = $currentMonthRevenue + $currentMonthPPOBRevenue;

    // Calculate total sales count
    $regularSalesCount = Transaction::where('store_id', $store->id)
      ->where('status', 'completed')
      ->count();

    $ppobSalesCount = StoreTransactionInstant::where('store_id', $store->id)
      ->where('status', 'success')
      ->count();

    $totalSalesCount = $regularSalesCount + $ppobSalesCount;

    return view('livewire.store.dashboard', [
      'currentMonthRevenue' => $currentMonthRevenue,
      'currentMonthPPOBRevenue' => $currentMonthPPOBRevenue,
      'totalMonthRevenue' => $totalMonthRevenue,
      'regularSalesCount' => $regularSalesCount,
      'ppobSalesCount' => $ppobSalesCount,
      'totalSalesCount' => $totalSalesCount
    ])->layout('components.layouts.app-dashboard');
  }
}
