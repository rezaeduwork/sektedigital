<?php

namespace App\Livewire\Components\User\Transaction;

use Livewire\Component;
use Livewire\Attributes\On;

class Item extends Component
{
  public $tx;
  public $complainText;
  public $status;
  public $showOthers = false;
  public function mount($tx, $status)
  {
    $this->tx = $tx;
    $this->status = $status;
  }
  public function toggleShowProduct()
  {
    $this->showOthers = !$this->showOthers;
  }
  public function complain()
  {
    if (!trim($this->complainText)) {
      $this->dispatch('alert-error', message: 'Deskripsi tidak boleh kosong!');
      return;
    } else if (strlen(trim($this->complainText)) < 50) {
      $this->dispatch('alert-error', message: 'Keterangan terlalu sedikit, Minimal 50 karakter!');
      return;
    }
    $this->tx->status = 'complain';
    $this->tx->save();
    transactionActivity($this->tx, auth()->id(), 'complain', $this->complainText);
    $this->dispatch('alert-success', message: 'Pesananmu berhasil di komplain! Silahkan tunggu seller memproses komplainmu.');
    $this->dispatch('reload')->to(\App\Livewire\User\Transaction::class);
  }
  public function finish()
  {
    \DB::beginTransaction();
    try {
      $this->tx->status = 'finished';
      $this->tx->save();
      $seller = $this->tx->store->user;
      $seller->balances()->create([
        'uid' => $seller->id . uniqid(),
        'type' => 'store_fund',
        'amount' => $this->tx->amount,
        'description' => 'Finished transaction #' . $this->tx->id,
        'status' => 'success',
        'name' => 'Finish Transaction',
      ]);
      $feeMerchant = $this->tx->payment->fee_total;
      $seller->balances()->create([
        'uid' => $seller->id . uniqid(),
        'type' => 'store_fund',
        'amount' => -abs($feeMerchant),
        'description' => 'Merchant Fee for transaction #' . $this->tx->id,
        'status' => 'success',
        'name' => 'Finish Transaction',
        'data' => json_encode([
          'label' => 'fee_merchant'
        ])
      ]);
      transactionActivity($this->tx, auth()->id(), 'finished', ('finished by user'));
      $seller->reloadBalance();
      $seller->notify(new \App\Notifications\TransactionNotification($this->tx, 'Transaction Finish Confirmation', 'Yeay, pesanan #' . $this->tx->id . ' sudah di konfirmasi selesai oleh user🥳'));
      \DB::commit();
    } catch (\Throwable $th) {
      \DB::rollback();
      $this->dispatch('alert-error', message: 'Gagal menyelesaikan pesanan.');
      return;
    }

    $this->dispatch('alert-success', message: 'Yeay, Pesanan kamu berhasil di selesaikan!');
    return $this->redirect(url('user/transaction') . '?tab=finished', navigate: true);
  }
  #[On('reload')]
  public function render()
  {
    return view('livewire.components.user.transaction.item');
  }
}
