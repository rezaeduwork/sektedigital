<div class="bg-white rounded-lg space-y-4 p-4" id="select-game">
  <div class="flex items-center space-x-4">
    <div class="font-semibold text-black sm:text-lg">Pilih Produk <span class="italic">{{$brand}}</span></div>
  </div>
  <input type="text" placeholder="Cari Produk" wire:model.live.debounce.400ms="searchProduct" class="w-full p-0 border !border-primary/30 max-sm:text-xs max-sm:placeholder:text-xs p-2 sm:px-4 sm:py-2 text-black bg-transparent outline-none ring-none rounded-lg">
  <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 max-sm:overflow-y-auto max-sm:px-5 max-sm:max-h-[300px]">
    @foreach ($list as $row)
    <button @click="$wire.selectProduct({{$row->id}})" class="border shadow rounded-lg w-full @if($product && $product->id == $row->id) bg-primary @else text-primary bg-white @endif text-center hover:bg-primary hover:text-white hover:shadow font-semibold group">
      <div class="p-2">
        {{$row->title}}
      </div>
      {{-- <div class="h-[1px] bg-white"></div> --}}
      <div class="@if($product && $product->id == $row->id) bg-primary @else bg-red-100 @endif group-hover:bg-primary rounded-b-lg p-2">Rp{{number_format($row->price,0,',','.')}}</div>
    </button>
    @endforeach
  </div>
  @if ($product)
    <div class="flex items-center space-x-4">
      <div class="font-semibold text-lg text-black">Isi Informasi Pengguna/Akun</div>
    </div>
    @forelse ($informations as $key => $row)
    <div>
      <div>
        <h2 class="font-semibold mb-2">{{$row['label']}} @if(isset($row['required']) && $row['required'] === false) <i class="font-normal">Optional</i> @endif</h2>
        <input type="{{$row['type'] ?:'number'}}" @input.debounce.500ms="$wire.fillAccount({{$key}},$event.target.value)" class="w-full p-0 border !border-primary/30 max-sm:p-2 max-sm:text-xs max-sm:placeholder:text-xs sm:px-4 sm:py-2 text-black bg-transparent outline-none ring-none rounded-lg">
      </div>
    </div>
    @empty

    @endforelse
  @endif
  <div class="flex items-center max-sm:justify-center max-sm:w-full space-x-2">
    <button class="text-primary border border-primary flex items-center space-x-1 rounded-lg max-sm:p-2 max-sm:text-xs sm:px-4 sm:py-2" @click="$dispatch('goBack')">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
      </svg>
      <div>Kembali</div>
    </button>
    <button class="text-white flex items-center space-x-1 rounded-lg max-sm:p-2 max-sm:text-xs sm:px-4 sm:py-2 @if(!$canSubmit) bg-primary/10 cursor-default @else bg-primary @endif" @click="$wire.pay()">
      Pilih Pembayaran
    </button>
  </div>
</div>
