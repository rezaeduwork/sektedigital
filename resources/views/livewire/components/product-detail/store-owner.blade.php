<div class="flex items-center justify-center mb-6">
  <div class="flex items-center space-x-7 w-full">
    <img src="{{storeProfile($store)}}" alt="" srcset="" class="w-[78px] h-[78px] shrink-0" />
    <div class="shrink-0">
      <div class="text-lg text-gray-700 font-semibold">{{$store->name}}</div>
      <div>Aktif {{\Carbon\Carbon::parse($store->updated_at)->diffForHumans()}}</div>
      <button class="bg-red-50 border border-primary text-primary px-4 py-2 mt-2 flex items-center space-x-2 font-semibold">
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
        <div class="font-semibold shrink-0 text-primary">4.5 dari 5 (102K)</div>
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
