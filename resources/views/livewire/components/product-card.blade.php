<div class="relative rounded-lg break-words border bg-white border-gray-200 shadow outline-none shadow-none rounded-t-xl" @click="Livewire.navigate('{{url($product->slug)}}')">
  <div class="flex-auto rounded-t-xl">
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
    <div class="flex flex-col gap-3 p-4">
      <a href="#!" class="text-decoration-none text-gray-500"><small>{{$product->category->name ?? ''}}</small></a>
      <div class="flex flex-col gap-2">
        <h3 class="text-base truncate"><a href="shop-single.html" class="hover:text-primary">{{\Str::limit($product->title,100,'...')}}</a></h3>
        <div class="flex items-center">
          <div class="flex flex-row gap-3">
            <small class="text-yellow-500 flex items-center">
              <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled"
                width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path
                  d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                  stroke-width="0" fill="currentColor"></path>
              </svg>
              <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled"
                width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path
                  d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                  stroke-width="0" fill="currentColor"></path>
              </svg>
              <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled"
                width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path
                  d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                  stroke-width="0" fill="currentColor"></path>
              </svg>
              <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled"
                width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path
                  d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                  stroke-width="0" fill="currentColor"></path>
              </svg>
              <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-half-filled"
                width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path
                  d="M12 1a.993 .993 0 0 1 .823 .443l.067 .116l2.852 5.781l6.38 .925c.741 .108 1.08 .94 .703 1.526l-.07 .095l-.078 .086l-4.624 4.499l1.09 6.355a1.001 1.001 0 0 1 -1.249 1.135l-.101 -.035l-.101 -.046l-5.693 -3l-5.706 3c-.105 .055 -.212 .09 -.32 .106l-.106 .01a1.003 1.003 0 0 1 -1.038 -1.06l.013 -.11l1.09 -6.355l-4.623 -4.5a1.001 1.001 0 0 1 .328 -1.647l.113 -.036l.114 -.023l6.379 -.925l2.853 -5.78a.968 .968 0 0 1 .904 -.56zm0 3.274v12.476a1 1 0 0 1 .239 .029l.115 .036l.112 .05l4.363 2.299l-.836 -4.873a1 1 0 0 1 .136 -.696l.07 -.099l.082 -.09l3.546 -3.453l-4.891 -.708a1 1 0 0 1 -.62 -.344l-.073 -.097l-.06 -.106l-2.183 -4.424z"
                  stroke-width="0" fill="currentColor"></path>
              </svg>
            </small>
            <div class="flex flex-row gap-1">
              <span class="text-gray-500 text-sm">4.5</span>
              <span class="text-gray-500 text-sm">(149)</span>
            </div>
          </div>
        </div>
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
          <button type="button"
            class="btn inline-flex items-center gap-x-2 btn-sm border border-primary text-primary">
            Beli Langsung
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
