<?php

namespace App\Livewire\Admin\Category;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

class Create extends Component
{
  use WithFileUploads;
  #[Validate('required|string|max:188')]
  public $name;
  #[Validate('required|image|max:512')]
  public $icon;
  public function store()
  {
    $this->validate();
    $filename = $this->icon->hashName();
    $this->icon->storeAs('public/', $filename);
    $category = new \App\Models\CategoryProduct();
    $category->name = $this->name;
    $category->icon = str_replace('public/', '', $filename);
    $category->save();
    $this->dispatch('alert-success', ['message' => 'Category created successfully']);
    $this->dispatch('reload')->to(\App\Livewire\Admin\Category::class);
  }
  public function render()
  {
    return view('livewire.admin.category.create');
  }
}
