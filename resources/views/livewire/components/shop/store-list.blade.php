<div class="py-6" @if($list->count() <= 0) style="display: none;" @endif id="store-list-section">
  <div class="flex flex-col md:flex-row justify-between lg:items-center gap-3 mb-6">
    <div class="text-2xl font-bold text-gray-600 flex items-center space-x-1">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-[24px] mr-2 !text-gray-600">
        <path d="M5.223 2.25c-.497 0-.974.198-1.325.55l-1.3 1.298A3.75 3.75 0 0 0 7.5 9.75c.627.47 1.406.75 2.25.75.844 0 1.624-.28 2.25-.75.626.47 1.406.75 2.25.75.844 0 1.623-.28 2.25-.75a3.75 3.75 0 0 0 4.902-5.652l-1.3-1.299a1.875 1.875 0 0 0-1.325-.549H5.223Z" />
        <path fill-rule="evenodd" d="M3 20.25v-8.755c1.42.674 3.08.673 4.5 0A5.234 5.234 0 0 0 9.75 12c.804 0 1.568-.182 2.25-.506a5.234 5.234 0 0 0 2.25.506c.804 0 1.567-.182 2.25-.506 1.42.674 3.08.675 4.5.001v8.755h.75a.75.75 0 0 1 0 1.5H2.25a.75.75 0 0 1 0-1.5H3Zm3-6a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-.75.75h-3a.75.75 0 0 1-.75-.75v-3Zm8.25-.75a.75.75 0 0 0-.75.75v5.25c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75v-5.25a.75.75 0 0 0-.75-.75h-3Z" clip-rule="evenodd" />
      </svg>
      <span class="mt-[2px]">{{$list->count()}}</span>
      <div class="mt-[2px]">Store</div>
    </div>
    <a href="http://sektedigital.test/shop/store" class="btn inline-flex items-center gap-x-2 bg-white text-gray-800 border-gray-200 border font-medium text-xs disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-primary hover:border-primary active:bg-primary active:border-primary focus:outline-none focus:ring-4 focus:ring-violet-300" wire:navigate="">
      Lihat Semua
    </a>
  </div>
  <div class="swiper swiper-store">
    <div class="swiper-wrapper flex flex-row items-stretch">
      @foreach ($list as $row)
      <button class="swiper-slide !h-[unset] border rounded-lg p-4 flex items-center gap-4 bg-white cursor-pointer hover:shadow" onclick="Livewire.navigate('{{url('s/'.$row->id)}}')">
        <!-- Logo -->
        <div class="flex-shrink-0">
          <img class="w-16 h-16 rounded-full" src="{{storeProfile($row)}}" />
        </div>
        <!-- Content -->
        <div class="flex-1">
          <h2 class="text-lg font-semibold text-left">{{$row->name}}</h2>
          <p class="text-sm text-gray-500 text-left">{{$row->user->name}}</p>
          <div class="flex items-center justify-start text-sm mt-2">
            <span class="text-red-600 font-semibold">477</span>
            <span class="ml-1 text-gray-500">Pengikut</span>
          </div>
        </div>
        <!-- Stats -->
        <div class="flex flex-col items-end">
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-5 w-5 text-red-500" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.01-.003.268-.108a.75.75 0 0 1 .558 0l.269.108.01.003zM10.404 2 4.25 4.461 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339L8 5.961 5.596 5l6.154-2.461z"/>
            </svg>
            <span class="text-red-600 font-semibold">{{$row->products()->count()}}</span>
            <span class="text-gray-500 text-sm">Produk</span>
          </div>
          @php
          $sumRating = $row->products()->join('product_ratings', 'products.id', '=', 'product_ratings.product_id')->count();
          $averageRating = $row->products()->join('product_ratings', 'products.id', '=', 'product_ratings.product_id')->avg('product_ratings.rating');
          @endphp
          <div class="flex items-center gap-2 mt-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L1 6.909l6.561-.955L10 0l2.439 5.954 6.561.955-4.244 4.636 1.122 6.545z"/></svg>
            <span class="text-red-600 font-semibold">{{round($averageRating,1)}} ({{number_format($sumRating)}})</span>
            <span class="text-gray-500 text-sm">Penilaian</span>
          </div>
        </div>
      </button>
      @endforeach
    </div>
    <div class="swiper-pagination-store-list mt-4 mx-auto justify-center text-center"></div>
  </div>
  @script
  <script>
    const swiper = new Swiper('.swiper-store', {
      // Optional parameters
      spaceBetween: 16,

      breakpoints: {
        0: {
          slidesPerView: 1.2,
          centeredSlides: true,
        },
        768: {
          slidesPerView: 1.4,
        },
        1024: {
          slidesPerView: 2.2,
        },
      },
      // If we need pagination
      pagination: {
        el: '.swiper-pagination-store-list',
        clickable: true,
      },
    });
  </script>
  @endscript
</div>
