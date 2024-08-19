<div class="mb-6">
  <div class="text-2xl font-bold mb-6">Rekomendasi</div>
  <div class="mb-6 grid grid-cols-4 gap-4">
    @foreach (\App\Models\Product::available()->take(16)->get() as $row)
    <livewire:components.popular-card :product="$row" :key="$row->id">
    @endforeach
  </div>
</div>
