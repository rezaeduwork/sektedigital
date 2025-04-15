<div class="my-4">
  <div class="container">
    <livewire:components.shop-category :category="$category">
    {{-- <hr /> --}}
    <a href="#store-list-section">
      <img src="{{url('assets/images/banner/cashback.jpg')}}" alt="" srcset="" class="w-full rounded-md my-2 sm:my-4">
    </a>
    <livewire:components.shop.store-list :category="$category">
    <div class="flex md:flex-row justify-between lg:items-center mb-4 sm:mb-6 gap-1 sm:gap-3 mt-2 sm:mt-4" id="product-list-section">
      <div class="sm:text-2xl font-bold text-gray-600 flex items-center space-x-1">
        {{-- <svg xmlns="http://www.w3.org/2000/svg" class="size-[24px] mr-2 !text-black" fill="currentColor" viewBox="0 0 16 16">
          <path d="M2.95.4a1 1 0 0 1 .8-.4h8.5a1 1 0 0 1 .8.4l2.85 3.8a.5.5 0 0 1 .1.3V15a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4.5a.5.5 0 0 1 .1-.3zM7.5 1H3.75L1.5 4h6zm1 0v3h6l-2.25-3zM15 5H1v10h14z"/>
        </svg> --}}
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-4 sm:size-[24px] mr-1 sm:mr-2 !text-red-600" viewBox="0 0 16 16">
          <path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6 .25 1.5-1.25 2-1.25 2C11 4 9 .5 6 0c.357 2 .5 4-2 6-1.25 1-2 2.729-2 4.5C2 14 4.686 16 8 16m0-1c-1.657 0-3-1-3-2.75 0-.75.25-2 1.25-3C6.125 10 7 10.5 7 10.5c-.375-1.25.5-3.25 2-3.5-.179 1-.25 2 1 3 .625.5 1 1.364 1 2.25C11 14 9.657 15 8 15"/>
        </svg>
        <span class="mt-[2px]">{{$list->total()}}</span>
        <div class="mt-[2px]">Products</div>
      </div>

      <!-- icon -->
      <div class="flex md:flex-row justify-between md:items-center gap-1 sm:gap-3">
        <div class="flex gap-1 sm:gap-3">
          <div>
            <!-- select option -->
            <select
              wire:model.live="orderBy"
              class="text-xs py-2 block w-full border-gray-300 rounded-lg focus:border-gray-300 focus:ring-gray-100 disabled:opacity-50 disabled:pointer-events-none sm:pl-3 sm:pr-7">
              <option value="" disabled>Urutkan</option>
              <option value="newest">Terbaru</option>
              <option value="lowest_price">Harga Terendah</option>
              <option value="best">Rating Terbaik</option>
              <option value="popular">Popular</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-4">
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
