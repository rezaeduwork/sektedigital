<?php

namespace App\Livewire\Components\Store\Transaction;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

class HistoryFinishButton extends Component
{
  use WithFileUploads;
  #[Validate('required|min:1')]
  public $finishNote;
  #[Validate('required|mimes:pdf,jpg,jpeg,png|max:5000')]
  public $finishFile;
  public $tx;
  public function mount($tx)
  {
    $this->tx = $tx;
  }
  public function completing()
  {
    $this->dispatch('close-confirmation');
    $this->validate();
    $this->tx->proof_text = $this->finishNote;
    $path = $this->finishFile->store(path: 'public/transaction_proof');
    $this->tx->proof_file = str_replace('public/transaction_proof/', '', $path);
    $this->tx->save();
    $this->dispatch('completing.' . $this->tx->id)->to(\App\Livewire\Components\Store\Transaction\HistoryItem::class);
  }
  public function render()
  {
    return view('livewire.components.store.transaction.history-finish-button');
  }
}
