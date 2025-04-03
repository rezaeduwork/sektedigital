<div class="mt-[3rem] lg:mt-[102px]">
  <div class="h-[400px] relative mb-8">
    <img src="{{ url('assets/images/banner/banner4.jpg') }}" alt="" srcset="" class="w-full object-cover h-full absolute top-0 left-0 z-[9]" style="clip-path: polygon(0 0, 100% 0, 100% 85%, 90% 100%, 0 100%);" />
    <div class="relative z-[10] text-white max-w-[968px] mx-auto h-full">
      <div class="container h-full flex flex-col justify-center">
        <h1 class="text-[50px] font-bold text-white leading-[54px] mb-[20px]">Marketplace Produk Digital <br />Terlengkap!</h1>
        <h6 class="text-[24px] text-white leading-[32px]">Platform yang menyediakan seluruh kebutuhan digital mu <br />dengan harga bersaing!</h6>
        @if(!auth()->check())
        <div class="mt-[32px] flex items-center space-x-2">
          <button type="button" data-bs-toggle="modal" data-bs-target="#userModal" class="bg-white text-primary rounded text-[16px] px-5 py-3 font-bold flex items-center justify-center space-x-2">
            <div>Daftar Sekarang</div>
          </button>
          <a href="#recommendation-section" class="text-white border-2 border-white rounded text-[16px] px-5 py-3 font-bold flex items-center justify-center space-x-2 inline">
            <div>Explore Produk</div>
          </a>
        </div>
        @else
        <div class="mt-[32px] flex space-x-2">
          <a href="#ppob-section" class="bg-white text-primary rounded text-[16px] px-5 py-3 font-bold flex items-center justify-center space-x-2 inline">
            <div>Top Up Instan</div>
          </a>
          <a href="#recommendation-section" class="text-white border-2 border-white rounded text-[16px] px-5 py-3 font-bold flex items-center justify-center space-x-2 inline">
            <div>Explore Produk</div>
          </a>
        </div>
        @endif
      </div>
    </div>
  </div>
  <div class="max-w-[968px] mx-auto">

    <div class="lg:mb-14 mb-8">
      <div class="container">
        <div class="grid grid-cols-12 gap-6">
          <div class="col-span-12">
            <div class="mb-10">
              <livewire:components.section-service>
            </div>
            {{-- <livewire:components.section-card-popular> --}}

            <livewire:components.section-recommendation>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
