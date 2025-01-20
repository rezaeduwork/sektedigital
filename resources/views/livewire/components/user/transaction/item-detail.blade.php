<div class="space-y-4">
  @php
  $product = $detail->product;
  @endphp
  <div class="flex items-center justify-between space-x-5">
    <div class="w-full">
      <div class=" mb-2 flex items-center space-x-2">
        <span class="text-black font-semibold">
          {{$product->store->name}}
        </span>
      </div>
      <div class="flex flex-row gap-5">
        <img src="{{ productImage($product->mainImage()) }}" alt="Ecommerce" class="w-16 h-16 rounded">
        <div class="flex flex-col gap-2">
          <div class="space-y-2">
            <!-- title -->
            <a href="#" class="text-inherit">
              <h6 class="font-black text-lg">{{ $product->title }}</h6>
            </a>
            <span class="text-gray-500 text-sm flex items-center space-x-1">
              <img src="{{ categoryImage($product->category) }}" alt="" srcset="" class="size-4">
              <span>{{ $product->category->name }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <!-- price -->
    <div class="text-right shrink-0 space-y-4 text-2xl font-bold text-primary">
      <div class="flex">
        <button class="flex items-center space-x-1 text-xs">
          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-3" viewBox="0 0 16 16">
            <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
            <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
          </svg>
          <span>Hubungi Penjual</span>
        </button>
      </div>
      <div>Rp{{number_format($detail->price * $detail->quantity,0,',','.')}}</div>
      <span class="text-xs font-medium me-2 px-2.5 py-0.5 rounded">{{$detail->quantity}} x Rp{{number_format($detail->price,0,',','.')}}</span>
    </div>
  </div>
  <div class="relative z-0 w-full">
    {{$tx->note ?? 'Tidak ada catatan'}}
  </div>
</div>
