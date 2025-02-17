<div class="flex items-center justify-center mb-6">
  <div class="flex items-center space-x-7 w-full">
    <img src="{{storeProfile($store)}}" alt="" srcset="" class="w-[78px] h-[78px] shrink-0" />
    <div class="shrink-0">
      <div class="text-lg text-gray-700 font-semibold">{{$store->name}}</div>
      <div>Aktif {{\Carbon\Carbon::parse($store->updated_at)->diffForHumans()}}</div>
      <button class="bg-violet-50 border border-primary text-primary px-4 py-2 mt-2 flex items-center space-x-2 font-semibold">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
        </svg>
        <div>
          Tanya Penjual
        </div>
      </button>
    </div>
    <div class="w-full grid grid-cols-2 lg:gap-6">
      <div class="flex items-center justify-between w-full border-b pb-2">
        <div>Produk</div>
        <div class="font-semibold shrink-0 text-primary">{{$store->products()->count()}}</div>
      </div>
      <div class="flex items-center justify-between w-full border-b pb-2">
        <div>Penilaian</div>
        @php
        $sumRating = $store->products()->join('product_ratings', 'products.id', '=', 'product_ratings.product_id')->count();
        $averageRating = $store->products()->join('product_ratings', 'products.id', '=', 'product_ratings.product_id')->avg('product_ratings.rating');
        @endphp
        <div class="font-semibold shrink-0 text-primary flex items-center space-x-1">
          <div>{{round($averageRating,1)}}</div>
          <svg xmlns="../www.w3.org/2000/svg.html"
            class="icon icon-tabler icon-tabler-star-filled text-yellow-300" width="14" height="14"
            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
            stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path
              d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
              stroke-width="0" fill="currentColor"></path>
          </svg>
          <div>dari 5 ({{number_format($sumRating)}})</div>
        </div>
      </div>
      <div class="flex items-center justify-between w-full border-b pb-2">
        <div>Terakhir Aktif</div>
        <div class="font-semibold shrink-0 text-primary">{{\Carbon\Carbon::parse($store->updated_at)->diffForHumans()}}</div>
      </div>
      <div class="flex items-center justify-between w-full border-b pb-2">
        <div>Bergabung</div>
        <div class="font-semibold shrink-0 text-primary">{{\Carbon\Carbon::parse($store->created_at)->diffForHumans()}}</div>
      </div>
    </div>
  </div>
</div>
