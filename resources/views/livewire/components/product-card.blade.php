<div class="relative rounded-lg break-words border bg-white border-gray-200 shadow outline-none shadow-none rounded-t-xl cursor-pointer" @click="Livewire.navigate('{{url($product->slug)}}')">
  <div class="flex flex-col h-full rounded-t-xl">
    <div class="text-center relative flex justify-center rounded-t-xl">
      {{-- <div class="absolute top-0 left-0">
        <span
          class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-violet-600 text-white">Sale</span>
      </div> --}}
      @php
      $mainImage = $product->mainImage();
      @endphp
      <a href="#!" class="rounded-t-xl"><img src="{{url('storage/'.$mainImage->name)}}" alt="{{$product->title}}"
          class="w-full h-auto rounded-t-xl"></a>
    </div>
    <div class="flex-grow flex flex-col gap-3 p-4">
      <a href="#!" class="text-decoration-none text-gray-500"><small>{{$product->category->name ?? ''}}</small></a>
      <div class="h-full flex flex-col gap-2">
        <h3 class="text-base truncate"><a href="#" class="hover:text-primary">{{\Str::limit($product->title,100,'...')}}</a></h3>
        <div class="flex-grow">
          {{\Str::limit($product->highlight,50,'...')}}
        </div>
        @if ($product->transactionDetails()->count() > 0)
          @php
          $total_rating = $product->ratings()->count();
          $total_rating_star = $total_rating > 0 ? $product->ratings()->avg('rating') : 0;
          @endphp
          <div class="flex items-center justify-between w-full">
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
            <div class="text-yellow-500 flex items-center gap-2 mt-3">
              @php
              $ordered = $product->transactionDetails()->whereHas('transaction', function($query) {
                $query->whereNotIn('status', ['unprocessed','cancelled','inspection','rejected']);
              })->count();
              @endphp
              <span class="text-gray-500 small">{{$ordered}} Terjual</span>
            </div>
          </div>
        @endif
      </div>
      <div class="flex justify-between items-center">
        <div>
          <span class="text-gray-900 font-semibold">Rp{{number_format($product->price,0,',','.')}}</span>
          {{-- <span class="line-through text-gray-500">$24</span> --}}
        </div>
        <div>
          <button type="button"
            class="btn inline-flex items-center gap-x-2 btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg"  fill="currentColor" class="text-primary size-4" viewBox="0 0 16 16">
              <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0M9 5.5V7h1.5a.5.5 0 0 1 0 1H9v1.5a.5.5 0 0 1-1 0V8H6.5a.5.5 0 0 1 0-1H8V5.5a.5.5 0 0 1 1 0"/>
            </svg>
          </button>
          {{-- <button type="button"
            class="btn inline-flex items-center gap-x-2 btn-sm border border-primary text-primary">
            Beli Langsung
          </button> --}}
        </div>
      </div>
    </div>
  </div>
</div>
