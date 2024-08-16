<div class="flex shadow border rounded-lg hover:shadow-lg h-[unset] bg-white cursor-pointer group">
  <a class="flex flex-col h-full w-full" href="{{url($product->slug)}}" wire:navigate>
    <!-- card -->
    <div class="rounded-t-lg mb-4 relative">
      <div class="text-center p-0">
        <!-- img -->
        <span class="flex justify-center rounded-t-lg h-[100px] md:h-[150px]"><img class="rounded-t-lg"
            src="{{ productImage($product->images()->whereType('main')->first()) }}" alt="Sekte Digital Product" /></span>
        <!-- text -->
      </div>
      <span class="inline-block px-2 py-1 text-sm align-baseline leading-none rounded-full bg-primary text-white font-semibold w-auto absolute top-[1rem] right-[1rem]">-45%</span>
    </div>
    <div class="p-4 h-full flex flex-col items-start">
      <h2 class="mb-1 text-base h-full">
        <span href="#" class="text-inherit group-hover:text-primary group-hover:font-semibold h-full">{{ \Str::limit($product->title, 30, '...') }}</span>
      </h2>
      <div>
        <span class="text-gray-900 text-base font-bold">Rp. {{number_format($product->price)}}</span>
        {{-- <span class="line-through text-gray-500">$24</span> --}}
      </div>
      <div class="text-yellow-500 flex items-center gap-2 mt-3">
        <!-- rating -->
        <div class="flex items-center">
          <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled" width="14"
            height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
            stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
              d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
              stroke-width="0" fill="currentColor" />
          </svg>
          <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled" width="14"
            height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
            stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
              d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
              stroke-width="0" fill="currentColor" />
          </svg>
          <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled" width="14"
            height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
            stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
              d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
              stroke-width="0" fill="currentColor" />
          </svg>
          <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled" width="14"
            height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
            stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
              d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
              stroke-width="0" fill="currentColor" />
          </svg>
          <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-half-filled" width="14"
            height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
            stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
              d="M12 1a.993 .993 0 0 1 .823 .443l.067 .116l2.852 5.781l6.38 .925c.741 .108 1.08 .94 .703 1.526l-.07 .095l-.078 .086l-4.624 4.499l1.09 6.355a1.001 1.001 0 0 1 -1.249 1.135l-.101 -.035l-.101 -.046l-5.693 -3l-5.706 3c-.105 .055 -.212 .09 -.32 .106l-.106 .01a1.003 1.003 0 0 1 -1.038 -1.06l.013 -.11l1.09 -6.355l-4.623 -4.5a1.001 1.001 0 0 1 .328 -1.647l.113 -.036l.114 -.023l6.379 -.925l2.853 -5.78a.968 .968 0 0 1 .904 -.56zm0 3.274v12.476a1 1 0 0 1 .239 .029l.115 .036l.112 .05l4.363 2.299l-.836 -4.873a1 1 0 0 1 .136 -.696l.07 -.099l.082 -.09l3.546 -3.453l-4.891 -.708a1 1 0 0 1 -.62 -.344l-.073 -.097l-.06 -.106l-2.183 -4.424z"
              stroke-width="0" fill="currentColor" />
          </svg>
        </div>
        <span class="text-gray-500 small">4.5</span>
      </div>
    </div>
  </a>
</div>
