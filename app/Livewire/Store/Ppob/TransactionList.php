<?php

namespace App\Livewire\Store\Ppob;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StoreTransactionInstant;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionList extends Component
{
  use WithPagination;

  public $search = '';
  public $status = '';
  public $dateRange = '';
  public $sortField = 'created_at';
  public $sortDirection = 'desc';

  public function updatingSearch()
  {
    $this->resetPage();
  }

  public function updatingStatus()
  {
    $this->resetPage();
  }

  public function updatingDateRange()
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

  public function render()
  {
    $store = Auth::user()->store;
    $query = StoreTransactionInstant::where('store_id', $store->id);

    if (!empty($this->search)) {
      $query->where(function ($q) {
        $q->where('code', 'like', '%' . $this->search . '%')
          ->orWhere('customer_no', 'like', '%' . $this->search . '%');
      });
    }

    if (!empty($this->status)) {
      $query->where('status', $this->status);
    }

    if (!empty($this->dateRange)) {
      if ($this->dateRange === 'today') {
        $query->whereDate('created_at', Carbon::today());
      } elseif ($this->dateRange === 'week') {
        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
      } elseif ($this->dateRange === 'month') {
        $query->whereMonth('created_at', Carbon::now()->month);
        $query->whereYear('created_at', Carbon::now()->year);
      } elseif ($this->dateRange === 'year') {
        $query->whereYear('created_at', Carbon::now()->year);
      }
    }

    $transactions = $query->orderBy($this->sortField, $this->sortDirection)
      ->paginate(10);

    // Get transaction statistics
    $totalCount = StoreTransactionInstant::where('store_id', $store->id)->count();
    $successCount = StoreTransactionInstant::where('store_id', $store->id)->where('status', 'success')->count();
    $failedCount = StoreTransactionInstant::where('store_id', $store->id)->where('status', 'failed')->count();
    $pendingCount = StoreTransactionInstant::where('store_id', $store->id)->whereIn('status', ['pending', 'processing'])->count();

    // Get today's revenue
    $todayRevenue = StoreTransactionInstant::where('store_id', $store->id)
      ->where('status', 'success')
      ->whereDate('created_at', Carbon::today())
      ->sum('total');

    // Get current month's revenue
    $monthRevenue = StoreTransactionInstant::where('store_id', $store->id)
      ->where('status', 'success')
      ->whereMonth('created_at', Carbon::now()->month)
      ->whereYear('created_at', Carbon::now()->year)
      ->sum('total');

    return view('livewire.store.ppob.transaction-list', [
      'transactions' => $transactions,
      'statuses' => StoreTransactionInstant::getStatuses(),
      'totalCount' => $totalCount,
      'successCount' => $successCount,
      'failedCount' => $failedCount,
      'pendingCount' => $pendingCount,
      'todayRevenue' => $todayRevenue,
      'monthRevenue' => $monthRevenue
    ])->layout('components.layouts.app-dashboard');
  }
}
