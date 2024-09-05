<div class="space-y-6 border-b pb-4">
  <div class="flex items-center justify-between">
    <div>NO. PESANAN {{$tx->id}}</div>
    <div class="flex items-center space-x-2 {{$tx->getStatusColor()}} font-semibold">
      <div>{{$tx->getStatusText()}}</div>
    </div>
  </div>
  <div class="flex items-center justify-between space-x-5">
    @php
    $product = $tx->details()->first()->product;
    @endphp
    <div class="w-full">
      <div class="flex flex-row gap-5">
        <img src="{{ productImage($product->mainImage()) }}" alt="Ecommerce" class="w-16 h-16">
        <div class="flex flex-col gap-2">
          <div>
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
      <div>Rp. {{number_format($tx->amount)}}</div>
      <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">{{$tx->details()->count()}} Produk</span>
      <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">{{$tx->details()->sum('quantity')}} Quantity</span>
    </div>
  </div>
  <div class="flex items-center justify-end w-full space-x-5">
    <div class="relative z-0 w-full">
      {{$tx->note ?? 'Tidak ada catatan'}}
    </div>
    <div class="flex items-center space-x-4 shrink-0">
      <button class="bg-primary text-white font-semibold px-5 py-2 shrink-0 rounded">Beli Lagi</button>
      <button class="hover:underline px-5">Detail Transaksi</button>
    </div>
  </div>
</div>
