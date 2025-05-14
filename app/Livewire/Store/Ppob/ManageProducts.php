<?php

namespace App\Livewire\Store\Ppob;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StoreProductInstant;
use Illuminate\Support\Facades\Auth;

class ManageProducts extends Component
{
  use WithPagination;

  public $search = '';
  public $category = '';
  public $brand = '';
  public $status = '';
  public $sortField = 'title';
  public $sortDirection = 'asc';
  public $selectedProductId;
  public $confirmingProductDeletion = false;

  protected $listeners = ['refreshProducts' => '$refresh'];

  public function updatingSearch()
  {
    $this->resetPage();
  }

  public function updatingCategory()
  {
    $this->resetPage();
  }

  public function updatingBrand()
  {
    $this->resetPage();
  }

  public function updatingStatus()
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

  public function toggleStatus($id)
  {
    $product = StoreProductInstant::find($id);
    $product->status = $product->status === 'active' ? 'inactive' : 'active';
    $product->save();

    session()->flash('message', 'Product status updated.');
  }

  public function confirmProductDeletion($id)
  {
    $this->confirmingProductDeletion = true;
    $this->selectedProductId = $id;
  }

  public function deleteProduct()
  {
    $product = StoreProductInstant::find($this->selectedProductId);
    $product->delete();

    $this->confirmingProductDeletion = false;

    session()->flash('message', 'Product removed successfully.');
  }

  public function cancelDeletion()
  {
    $this->confirmingProductDeletion = false;
  }

  public function render()
  {
    $store = Auth::user()->store;
    $query = StoreProductInstant::where('store_id', $store->id);

    if (!empty($this->search)) {
      $query->where(function ($q) {
        $q->where('title', 'like', '%' . $this->search . '%')
          ->orWhere('code', 'like', '%' . $this->search . '%')
          ->orWhere('description', 'like', '%' . $this->search . '%');
      });
    }

    if (!empty($this->category)) {
      $query->where('category', $this->category);
    }

    if (!empty($this->brand)) {
      $query->where('brand', $this->brand);
    }

    if (!empty($this->status)) {
      $query->where('status', $this->status);
    }

    $products = $query->orderBy($this->sortField, $this->sortDirection)
      ->paginate(10);

    $categories = StoreProductInstant::where('store_id', $store->id)
      ->distinct()
      ->pluck('category')
      ->filter()
      ->toArray();

    $brands = StoreProductInstant::where('store_id', $store->id)
      ->when(!empty($this->category), function ($q) {
        return $q->where('category', $this->category);
      })
      ->distinct()
      ->pluck('brand')
      ->filter()
      ->toArray();

    return view('livewire.store.ppob.manage-products', [
      'products' => $products,
      'categories' => $categories,
      'brands' => $brands
    ])->layout('components.layouts.app-dashboard');
  }
}
