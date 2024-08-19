<?php

namespace App\Livewire\Store\Product;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

class Edit extends Component
{
  public $product;
  use WithFileUploads;
  #[Validate('required|numeric', onUpdate: false)]
  public $category_id;
  #[Validate('required|string|max:199', onUpdate: false)]
  public $title;
  #[Validate('required|string|max:100', onUpdate: false)]
  public $highlight;
  #[Validate('nullable|image|max:1024', onUpdate: false)]
  public $main_photo;
  #[Validate(['additional_photo.*' => 'nullable|image|max:1024'], onUpdate: false)]
  public $additional_photo = [];
  #[Validate('required|min:20|max:1000', onUpdate: false)]
  public $description;
  #[Validate('required|numeric|max:1000000000', onUpdate: false)]
  public $price;
  #[Validate('required|numeric|max:1000000000', onUpdate: false)]
  public $stock;
  public function update() {
    $this->validate();
    $category = \App\Models\CategoryProduct::find($this->category_id);
    $slug = \Str::slug($this->title, '-').'-'.auth()->id().uniqid();
    $this->product->update([
      'title' => $this->title,
      'highlight' => $this->highlight,
      'description' => $this->description,
      'price' => $this->price,
      'slug' => $slug,
      'stock' => $this->stock,
      'store_id' => auth()->user()->store->id,
      'category_product_id' => $this->category_id
    ]);
    if ($this->main_photo) {
      $existingImage = $product->images()->whereType('main')->first();
      $path = $this->main_photo->store(path: 'public');
      $product->images()->create([
        'name' => str_replace('public/','',$path),
        'type' => 'main'
      ]);
      if (\Storage::disk('public')->exists($existingImage->name)) {
        \Storage::disk('public')->delete($existingImage->name);
      }
      $existingImage->delete();
    }
    if (sizeof($this->additional_photo) > 0) {
      $existingImages = $product->images()->whereType('additional')->get();
      foreach ($this->additional_photo as $row) {
        $path = $row->store(path: 'public');
        $product->images()->create([
          'name' => str_replace('public/','',$path),
          'type' => 'additional'
        ]);
      }
      foreach ($existingImages as $row) {
        if (\Storage::disk('public')->exists($row->name)) {
          \Storage::disk('public')->delete($row->name);
        }
        $row->delete();
      }
    }
    $this->dispatch('alert-success', message: 'Berhasil mengubah produk!');
    $this->redirect('/store/product', navigate: true);
  }
  public function mount($slug) {
    $this->product = \App\Models\Product::whereSlug($slug)->firstOrFail();
    $this->category_id = $this->product->category_product_id;
    $this->title = $this->product->title;
    $this->highlight = $this->product->highlight;
    $this->description = $this->product->description;
    $this->price = $this->product->price;
    $this->stock = $this->product->stock;
  }
  public function render()
  {
    return view('livewire.store.product.edit')->layout('components.layouts.app-dashboard');
  }
}
