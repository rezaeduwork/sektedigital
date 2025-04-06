<div class="bg-white rounded-lg space-y-4 p-4" id="select-game">
  <div class="flex items-center space-x-4">
    <div class="font-semibold text-black text-lg">Pilih Produk <span class="italic">{{$brand}}</span></div>
  </div>
  <div class="grid grid-cols-5 gap-3">
    @foreach ($list as $row)
    <button @click="$wire.selectProduct({{$row->id}})" class="border border-primary/50 rounded-lg w-full p-2 @if($product && $product->id == $row->id) bg-primary @else text-primary bg-primary/10 @endif text-center hover:bg-primary hover:text-white hover:shadow font-semibold">
      {{$row->title}}
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
        <h2 class="font-semibold mb-2">{{$row['label']}}</h2>
        <input type="{{$row['type'] ?:'number'}}" @input.debounce.500ms="$wire.fillAccount({{$key}},$event.target.value)" class="w-full p-0 border !border-primary px-4 py-2 text-black bg-transparent outline-none ring-none rounded-lg">
      </div>
    </div>
    @empty

    @endforelse
  @endif
  <div class="flex items-center space-x-2">
    <button class="text-black border border-primary flex items-center space-x-1 rounded-lg px-4 py-2" @click="$dispatch('goBack')">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
      </svg>
      <div>Kembali</div>
    </button>
    <button class="text-white flex items-center space-x-1 rounded-lg px-4 py-2 @if(!$product || !$account) bg-primary/10 cursor-default @else bg-primary @endif" @click="$wire.pay()">
      Pilih Pembayaran
    </button>
  </div>
</div>
