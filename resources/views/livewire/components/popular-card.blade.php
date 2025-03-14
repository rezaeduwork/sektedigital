<a class="swiper-slide shadow border rounded-lg hover:shadow-lg h-[unset] bg-white cursor-pointer group z-[1]" href="#" @click="Livewire.navigate('{{url($product->slug)}}')">
  @php
  $total_rating = $product->ratings()->count();
  $total_rating_star = $total_rating > 0 ? $product->ratings()->avg('rating') : 0;
  @endphp
  <div class="flex flex-col h-full w-full">
    <!-- card -->
    <div class="rounded-t-lg relative">
      <div class="text-center p-0">
        <!-- img -->
        <span class="flex justify-center rounded-t-lg h-[100px] md:h-[150px]"><img class="rounded-t-lg w-full h-full"
            src="{{ productImage($product->images()->whereType('main')->first()) }}" alt="Sekte Digital Product" /></span>
        <!-- text -->
      </div>
      {{-- <span class="inline-block px-2 py-1 text-sm align-baseline leading-none rounded-full bg-primary text-white font-semibold w-auto absolute top-[1rem] right-[1rem]">-45%</span> --}}
    </div>
    <div class="p-4 h-full flex flex-col items-start">
      <div class="text-gray-600 text-xs flex items-center space-x-1 mb-2">
        <img src="{{url('storage/'.$product->category->icon)}}" alt="" srcset="" class="size-4 shrink-0" />
        <div>
          {{$product->category->name}}
        </div>
      </div>
      <h2 class="mb-2 text-base h-full leading-[20px]">
        <span href="#" class="text-inherit font-semibold group-hover:text-primary group-hover:font-bold h-full block break-all">{{ \Str::limit($product->title, 30, '...') }}</span>
      </h2>
      <div class="flex items-center @if ($total_rating_star > 0) justify-between @endif w-full">
        @if ($total_rating_star > 0)
        <div class="text-yellow-500 flex items-center gap-2 mt-3">
          <!-- rating -->
          <div class="flex items-center">
            <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler
            @if($total_rating_star < 5)
            icon-tabler-star-half-filled
            @else
            icon-tabler-star-filled
            @endif
            "
              width="14" height="14" viewBox="0 0 24 24" stroke-width="2"
              stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
              <path
                d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                stroke-width="0" fill="currentColor"></path>
            </svg>
          </div>
          <span class="text-gray-500 small">{{round($total_rating_star,1)}}</span>
        </div>
        @endif
        @php
        $ordered = $product->transactionDetails()->whereHas('transaction', function($query) {
          $query->whereNotIn('status', ['unprocessed','cancelled','inspection','rejected']);
        })->count();
        @endphp
        @if ($ordered > 0 || mt_rand(1,2) === 1)
        <div class="text-yellow-500 flex items-center gap-2 mt-3">
          <span class="text-gray-500 small">{{$ordered}} Terjual</span>
        </div>
        @endif
      </div>
      <div>
        <span class="text-gray-900 text-base font-bold text-lg">Rp. {{number_format($product->price)}}</span>
        {{-- <span class="line-through text-gray-500">$24</span> --}}
      </div>
    </div>
  </div>
</a>
