<div class="space-y-4">
  <div class="space-y-4 bg-white rounded-lg p-2 sm:p-4 @if($brand) hidden @endif">
    {{-- <div class="font-semibold text-black text-lg">Pilih Game</div> --}}
    <div class="">
      <input type="text" name="" id="" class="border border-gray-300 text-gray-900 rounded-lg !ring-none !outline-none focus:border-gray-300 shadow-primary block p-2 px-3 disabled:opacity-50 disabled:pointer-events-none w-full text-sm"
      placeholder="Cari" wire:model.live.debounce.500ms="search">
    </div>
    <div class="text-gray-700 rounded-md grid grid-cols-4 sm:grid-cols-8 gap-4 w-full">
      @foreach ($list as $row)
      <a href="#ppob-section" @click="$wire.changeProductSelected('{{$row->brand}}')" class="shrink-0 flex flex-col items-center space-y-2 cursor-pointer w-full">
        <img src="{{productInstantImage($row)}}" alt="{{$row->brand}} Image" alt="" srcset="" class="w-full h-auto rounded-md">
        <div class="font-bold w-full text-center leading-5 text-xs">{{$row->brand}}</div>
      </a>
      @endforeach
    </div>
    <div class="space-y-2 hidden">
      <div class="text-white text-2xl font-semibold border-b-2 border-white pb-2">Cara Instan Top Up <span class="font-black">MOBILE LEGEND</span> di {{config('app.name')}}</div>
      <div>
        Silahkan perhatikan cara dibawah ini
      </div>
      <ul class="list-disc list-inside">
        <li>Buka aplikasi Shopee di gadget atau melalui shopee.co.id</li>
        <li>Pada menu utama, pilih dan klik menu Pulsa, Tagihan & Hiburan</li>
        <li>Pilih menu Voucher Game</li>
        <li>Cari dan pilih kolom UniPin Voucher</li>
      </ul>
    </div>
  </div>
  <div wire:loading.class="!flex" wire:target="changeProductSelected" class="p-4 hidden items-center justify-center w-full" id="select-game">
    @include('components.spinner')
  </div>
  @if ($brand)
  <div wire:loading.remove wire:target="changeProductSelected">
    <livewire:components.home.services.game-online.select-product :brand="$brand" :key="'game-'.str_replace(' ','-',$brand)" />
  </div>
  @endif
</div>
