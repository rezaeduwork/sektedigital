<?php

namespace App\Livewire\User\Wallet;

use Livewire\Component;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class RecentPayments extends Component
{
  public $recentPayments = [];
  public $limit = 5;

  public function mount()
  {
    $this->loadRecentPayments();
  }

  #[On('reload')]
  public function loadRecentPayments()
  {
    $this->recentPayments = Payment::where('user_id', Auth::id())
      ->where('transaction_type', 'deposit')
      ->orderBy('created_at', 'desc')
      ->limit($this->limit)
      ->get();
  }

  public function render()
  {
    return view('livewire.user.wallet.recent-payments');
  }
}
