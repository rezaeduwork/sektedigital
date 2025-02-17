<?php

namespace App\Livewire\Components\User\Transaction;

use Livewire\Component;

class RatingBox extends Component
{
  public $rating = 0;
  public $feedback = '';
  public $detailTx;
  public $product;
  public function mount($product, $detailTx)
  {
    $this->detailTx = $detailTx;
    $this->product = $product;
    $existingRating = $this->product->ratings()->whereTransaction_id($this->detailTx->transaction_id)->whereUser_id(auth()->id())->first();
    if ($existingRating) {
      $this->rating = $existingRating->rating;
      $this->feedback = $existingRating->feedback;
    }
  }
  public function storeRating()
  {
    $existingRating = $this->product->ratings()->whereTransaction_id($this->detailTx->transaction_id)->whereUser_id(auth()->id())->first();
    if (!$existingRating) {
      $this->product->ratings()->create([
        'rating' => $this->rating,
        'feedback' => $this->feedback,
        'transaction_id' => $this->detailTx->transaction_id,
        'user_id' => auth()->id()
      ]);
    } else {
      $existingRating->update([
        'rating' => $this->rating,
        'feedback' => $this->feedback,
      ]);
    }

    $this->dispatch('alert-success', message: 'Yeay, Rating disimpan 😊 Terimakasih');
  }
  public function render()
  {
    return view('livewire.components.user.transaction.rating-box');
  }
}
