<div class="w-full mx-auto px-4 pb-4">
  <div class="flex flex-col items-center gap-2">
    <!-- Profile Card -->
    <div class="bg-primary p-4 w-full shrink-0 flex items-center rounded">
      <div class="w-full space-y-2">
        <div class="flex items-center gap-4 w-full">
          <div class="flex-shrink-0">
            <img class="w-16 h-16 rounded-full" src="{{storeProfile($store)}}" />
          </div>
          <div class="flex-1">
            <h1 class="text-white text-xl font-bold">{{$store->name}}</h1>
            <p class="text-gray-400 text-sm">Aktif 9 menit lalu</p>
          </div>

        </div>
        <!-- Stats Grid -->
        <div class="flex gap-2 w-full">
          <div class="flex items-center gap-2">
            <span class="text-white">Produk:</span>
            <span class="text-red-500 font-semibold">108</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-white">Pengikut:</span>
            <span class="text-red-500 font-semibold">477</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-white">Penilaian:</span>
            <span class="text-red-500 font-semibold">4.7</span>
            <span class="text-white">(414 Penilaian)</span>
          </div>
        </div>
      </div>
      <div class="flex flex-col w-[200px] gap-2">
        <button class="bg-white/10 text-white px-6 py-2 rounded hover:bg-white/20 w-full">
            Ikuti
        </button>
        <button class="bg-white/10 text-white px-6 py-2 rounded hover:bg-white/20 w-full">
            Chat
        </button>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="border-b border-gray-200">
    <div class="flex items-center justify-between space-x-4">
      <div class="flex gap-8 overflow-x-auto">
        <div class="@if(!$filterCategory) border-b-4 border-primary @endif py-4 shrink-0" @click="$wire.set('filterCategory', '')">
          <a href="#" class="text-primary">Semua Produk</a>
        </div>
        @foreach ($categories as $rowCategory)
        <div class="py-4 shrink-0 @if($filterCategory == $rowCategory->id) border-b-4 border-primary @endif" @click="$wire.set('filterCategory', {{$rowCategory->id}})">
          <a href="#" class="text-primary">{{$rowCategory->name}}</a>
        </div>
        @endforeach
      </div>
      <!-- icon -->
      <div class="flex flex-col md:flex-row justify-between md:items-center gap-3 py-4">
        <div class="flex gap-3">
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
  </nav>

  <div class="grid lg:grid-cols-3 md:grid-cols-3 gap-4 mt-4">
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

  @if ($list->total() > 0)
    @if(!$hasMorePages)
    <div class="text-center py-4 text-gray-500">
      Halaman Terakhir
    </div>
    @endif
  @else
    <div class="text-center py-4 text-gray-500">
      Tidak ada produk.
    </div>
  @endif

</div>
