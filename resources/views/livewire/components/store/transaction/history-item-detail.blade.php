<div class="space-y-6">
  <div class="flex items-center justify-between space-x-5">
    @php
    $product = $detail->product;
    @endphp
    <div class="shrink-0">
      <div class="flex items-center">
        <input id="product-{{$detail->id}}-checkbox" type="checkbox" value="{{$detail->id}}" @change="$wire.select({{$detail->id}})"
        @if($isSelected)
        checked="checked"
        @endif
        class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
      </div>
    </div>
    <div class="w-full">
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
    <div class="text-right shrink-0 space-y-2">
      <div class="font-bold text-primary">Rp{{number_format($detail->subtotal,0,',','.')}}</div>
      <div class="flex items-center">
        <span class="text-xs font-medium me-2 px-2.5 py-0.5 rounded">{{$detail->quantity}} x Rp{{number_format($detail->price,0,',','.')}}</span>
      </div>
    </div>
  </div>
  <div class="flex items-center justify-end w-full space-x-5">
    <div class="relative z-0 w-full text-sm">
      {{$detail->note ?? 'Tidak ada catatan'}}
    </div>
  </div>
</div>
