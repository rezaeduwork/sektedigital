<div class="mb-6 product-content">
  <div class="flex">
    <div class="w-full flex items-center justify-between">
      <h2 class="text-md lg:text-lg flex items-center">
        <img src="{{url('assets/images/quality.png')}}" alt="" srcset="" class="w-[24px] h-[24px]" />
        <div class="ms-2 font-black text-2xl">Rekomendasi</div>
      </h2>
      <a href="{{url('shop')}}"
      class="btn inline-flex items-center gap-x-2 bg-gray-100 text-gray-800 border-gray-200 border disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-primary hover:border-primary active:bg-primary active:border-primary focus:outline-none focus:ring-4 focus:ring-red-300"
      wire:navigate>
        Lihat Semua
      </a>
    </div>
  </div>
  <div class="swiper-container swiper">
    <div class="py-5 grid md:grid-cols-3 gap-6 lg:grid-cols-4">
      @foreach($this->recommendationProduct as $row)
      <livewire:components.basic-card :product="$row" :key="$row->id">
      @endforeach
    </div>
    @if ($on_page >= 40 || $total <= $on_page)
    <a class="w-full text-center p-4 text-[#0000EE] font-semibold block"
    href="{{url('shop')}}"
    wire:navigate
    >Lihat Semua Produk</a>
    @else
    <div x-intersect.full="$wire.loadMore()" class="p-4 w-full flex justify-center">
      <div wire:loading wire:key="loadMore" wire:target="loadMore">
        @include('components.spinner', ['spinnerSize' => 'lg'])
      </div>
    </div>
    @endif
  </div>
</div>
