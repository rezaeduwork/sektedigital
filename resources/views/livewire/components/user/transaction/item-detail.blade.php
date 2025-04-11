<div class="space-y-4">
  @php
  $product = $detail->product;
  @endphp
  <div class="flex items-center justify-between space-x-5">
    <div class="w-full">
      <div class="flex flex-row gap-2 sm:gap-5">
        <img src="{{ productImage($product->mainImage()) }}" alt="Ecommerce" class="w-16 h-16 rounded">
        <div class="flex flex-col gap-2">
          <div class="space-y-2">
            <!-- title -->
            <a href="#" class="text-inherit">
              <a class="font-black text-lg text-link" href="{{url($product->slug)}}" wire:navigate>{{ $product->title }}</a>
            </a>
            <span class="text-gray-500 text-xs sm:text-sm flex items-center space-x-1">
              <img src="{{ categoryImage($product->category) }}" alt="" srcset="" class="size-3 sm:size-4">
              <span>{{ $product->category->name }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <!-- price -->
    <div class="text-right shrink-0 space-y-2 sm:space-y-4 text-2xl font-bold text-primary">
      <div class="max-sm:text-lg">Rp{{number_format($detail->price * $detail->quantity,0,',','.')}}</div>
      <span class="text-xs font-medium sm:me-2 sm:px-2.5 py-0.5 rounded">{{$detail->quantity}} x Rp{{number_format($detail->price,0,',','.')}}</span>
    </div>
  </div>
  <div class="relative z-0 w-full">
    {{$detail->note ?? 'Tidak ada catatan'}}
  </div>
  @if ($detail->transaction->status == 'finished')
  <livewire:components.user.transaction.rating-box :product="$detail->product" :detailTx="$detail">
  @endif
</div>
