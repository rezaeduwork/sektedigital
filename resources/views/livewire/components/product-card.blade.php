<div class="relative rounded-lg break-words border bg-white border-gray-200 shadow outline-none shadow-none rounded-t-lg cursor-pointer" @click="Livewire.navigate('{{url($product->slug)}}')">
  <div class="flex flex-col h-full rounded-t-lg">
    <div class="text-center relative flex justify-center rounded-t-lg">
      {{-- <div class="absolute top-0 left-0">
        <span
          class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-violet-600 text-white">Sale</span>
      </div> --}}
      @php
      $mainImage = $product->mainImage();
      @endphp
      <a href="#!" class="rounded-t-lg"><img src="{{url('storage/'.$mainImage->name)}}" alt="{{$product->title}}"
          class="w-full h-auto rounded-t-lg"></a>
    </div>
    <div class="flex-grow flex flex-col gap-1 sm:gap-3 p-2 sm:p-4">
      <div class="h-full">
        <a href="#!" class="text-decoration-none text-gray-500"><small>{{$product->category->name ?? ''}}</small></a>
        <div class="h-full flex flex-col gap-2">
          <h3 class="truncate sm:text-lg"><a href="#" class="hover:text-primary sm:font-bold">{{\Str::limit($product->title,100,'...')}}</a></h3>
          <div class="flex-grow text-xs sm:text-sm">
            {{\Str::limit($product->highlight,50,'...')}}
          </div>
        </div>
      </div>
      <div>
        <div class="flex justify-between items-center">
          <div>
            <span class="text-red-600 font-semibold sm:text-lg w-full">Rp{{number_format($product->price,0,',','.')}}</span>
            {{-- <span class="line-through text-gray-500">$24</span> --}}
          </div>
          @if ($product->transactionDetails()->count() > 0)
            @php
            $total_rating = $product->ratings()->count();
            $total_rating_star = $total_rating > 0 ? $product->ratings()->avg('rating') : 0;
            @endphp
            <div class="flex items-center justify-between shrink-0 space-x-1 sm:space-x-2">
              <div class="text-yellow-500 flex items-center gap-1 sm:gap-2">
                @php
                $ordered = $product->transactionDetails()->whereHas('transaction', function($query) {
                  $query->whereNotIn('status', ['unprocessed','cancelled','inspection','rejected']);
                })->count();
                @endphp
                <span class="text-black small">{{$ordered}} Terjual</span>
              </div>
              <div class="text-yellow-500 flex items-center">
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
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
