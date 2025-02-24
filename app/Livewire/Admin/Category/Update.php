<?php

namespace App\Livewire\Admin\Category;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class Update extends Component
{
  use WithFileUploads;
  public $category;
  #[Validate('required|string|max:188')]
  public $name;
  #[Validate('nullable|image|max:512')]
  public $icon;
  public function mount($id)
  {

    $this->category = \App\Models\CategoryProduct::find($id);
    $this->name = $this->category->name;
  }
  public function update()
  {
    $this->validate();
    if ($this->icon) {
      // Delete Existing
      if ($this->category->icon && \Storage::exists('public/' . $this->category->icon)) {
        \Storage::delete('public/' . $this->category->icon);
      }
      $filename = $this->icon->hashName();
      $this->icon->storeAs('public/', $filename);
      $this->category->icon = str_replace('public/', '', $filename);
    }
    $this->category->name = $this->name;
    $this->category->save();
    $this->dispatch('alert-success', ['message' => 'Category updated successfully']);
    $this->dispatch('reload')->to(\App\Livewire\Admin\Category::class);
  }
  public function render()
  {
    return view('livewire.admin.category.update');
  }
}
