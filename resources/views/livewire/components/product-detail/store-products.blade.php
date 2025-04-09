<div class="mb-4 sm:mb-6">
  <div class="text-lg sm:text-2xl font-bold mb-6 flex items-center space-x-1">Produk lain</div>
  <div class="mb-6 grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-4">
    @foreach ($store->products()->available()->take(4)->latest()->get() as $row)
    <livewire:components.popular-card :product="$row" :key="$row->id">
    @endforeach
  </div>
</div>
