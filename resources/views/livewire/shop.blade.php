<div class="my-4">
  <div class="container">
    <livewire:components.shop-category :category="$category">
      <hr />
      <div class="flex flex-col md:flex-row justify-between lg:items-center mb-6 gap-3 mt-4">
        <div>
          <p>
            <span class="text-gray-900">{{$list->total()}}</span>
            Products found
          </p>
        </div>

        <!-- icon -->
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-3">
          <div class="flex gap-3">
            <div class="flex-grow">
              <!-- select option -->
              <select
                class="text-xs py-2 block w-full border-gray-300 rounded-lg focus:border-gray-300 focus:ring-gray-100 disabled:opacity-50 disabled:pointer-events-none pl-3 pr-7">
                <option selected>Show: 50</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
              </select>
            </div>
            <div>
              <!-- select option -->
              <select
                class="text-xs py-2 block w-full border-gray-300 rounded-lg focus:border-gray-300 focus:ring-gray-100 disabled:opacity-50 disabled:pointer-events-none pl-3 pr-7">
                <option selected="">Sort by: Featured</option>
                <option value="Low to High">Price: Low to High</option>
                <option value="High to Low">Price: High to Low</option>
                <option value="Release Date">Release Date</option>
                <option value="Avg. Rating">Avg. Rating</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="grid lg:grid-cols-3 md:grid-cols-3 gap-4">
        @foreach ($list as $row)
        <livewire:components.product-card :key="$row->id" :product="$row" />
        @endforeach
      </div>

    {{-- Intersection Observer Target --}}
    @if($hasMorePages)
    <div x-data
    x-intersect="$wire.loadMore()"
    class="flex items-center justify-center p-4 mb-4">
      <div wire:loading class="text-gray-500">
          <svg class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
      </div>
    </div>
    @endif

    @if(!$hasMorePages)
      <div class="text-center py-4 text-gray-500">
        Halaman Terakhir
      </div>
    @endif

  </div>
</div>
