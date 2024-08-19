<div class="mb-6 product-content">
  <div class="flex">
    <div class="w-full flex items-center justify-between">
      <h2 class="text-md lg:text-lg flex items-center">
        <svg xmlns="../www.w3.org/2000/svg.html" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-star text-primary">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
          <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path>
        </svg>
        <div class="ms-2 font-black text-2xl">Populer</div>
      </h2>
      <a
      href="{{url('shop')}}"
      class="btn inline-flex items-center gap-x-2 bg-gray-100 text-gray-800 border-gray-200 border disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-primary hover:border-primary active:bg-primary active:border-primary focus:outline-none focus:ring-4 focus:ring-red-300"
      wire:navigate>
        Lihat Semua
      </a>
    </div>
  </div>
  <div class="swiper-container swiper" id="swiper-1" data-pagination-type="" data-speed="400"
    data-space-between="24" data-pagination="false" data-navigation="true" data-autoplay="true"
    data-autoplay-delay="3000" data-effect="slide"
    data-breakpoints='{"390": {"slidesPerView": 2}, "768": {"slidesPerView": 3}, "1024": {"slidesPerView": 4}}'>
    <div class="swiper-wrapper py-5 items-stretch">
      <!-- item -->
      @php
      $popularProduct = \App\Models\Product::available()->withCount(['logs' => function($query) {
        $query->where('activity', 'view');
      }])->orderBy('logs_count')->take(8)->get();
      @endphp
      @foreach ($popularProduct as $row)
        <livewire:components.popular-card :product="$row" :key="$row->id">
      @endforeach
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination !bottom-14"></div>
  </div>
</div>
