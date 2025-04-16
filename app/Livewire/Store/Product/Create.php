<?php

namespace App\Livewire\Store\Product;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

class Create extends Component
{
  use WithFileUploads;
  #[Validate('required|numeric', onUpdate: false)]
  public $category_id;
  #[Validate('required|string|max:199', onUpdate: false)]
  public $title;
  #[Validate('required|string|max:100', onUpdate: false)]
  public $highlight;
  #[Validate('required|image|max:1024', onUpdate: false)]
  public $main_photo;
  #[Validate(['additional_photo.*' => 'nullable|image|max:1024'], onUpdate: false)]
  public $additional_photo = [];
  #[Validate('required|min:20|max:1000', onUpdate: false)]
  public $description;
  #[Validate('required|numeric|min:1|max:1000000000', onUpdate: false)]
  public $price;
  #[Validate('required|numeric|max:1000000000', onUpdate: false)]
  public $stock;
  #[Validate('required|numeric|max:60', onUpdate: false)]
  public $delivered_duration;
  #[Validate('required|string|max:1', onUpdate: false)]
  public $delivered_duration_type;
  public function mount()
  {
    $this->delivered_duration = 3;
    $this->delivered_duration_type = 'd';
  }
  public function store()
  {
    $this->validate();
    $category = \App\Models\CategoryProduct::find($this->category_id);
    $slug = \Str::slug($this->title, '-') . '-' . auth()->id() . uniqid();
    $product = \App\Models\Product::create([
      'title' => $this->title,
      'description' => $this->description,
      'highlight' => $this->highlight,
      'price' => $this->price,
      'slug' => $slug,
      'stock' => $this->stock,
      'store_id' => auth()->user()->store->id,
      'category_product_id' => $this->category_id,
      'delivered_duration' => $this->delivered_duration,
      'delivered_duration_type' => $this->delivered_duration_type,
    ]);
    $path = $this->main_photo->store(path: 'public');
    $product->images()->create([
      'name' => str_replace('public/', '', $path),
      'type' => 'main'
    ]);
    if (sizeof($this->additional_photo) > 0) {
      foreach ($this->additional_photo as $row) {
        $path = $row->store(path: 'public');
        $product->images()->create([
          'name' => str_replace('public/', '', $path),
          'type' => 'additional'
        ]);
      }
    }
    $this->dispatch('alert-success', message: 'Berhasil membuat produk!');
    $this->redirect('/store/product', navigate: true);
  }
  public function render()
  {
    return view('livewire.store.product.create')->layout('components.layouts.app-dashboard');
  }
}
