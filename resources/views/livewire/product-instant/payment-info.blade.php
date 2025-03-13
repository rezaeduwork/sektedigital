<div class="w-[350px] shrink-0 sticky top-[110px] self-start">
  <div class="bg-white border border-violet-100 p-4 rounded-lg space-y-4">
    <h2 class="font-semibold">Ringkasan Transaksi</h2>
    <hr class="my-2" />
    @if ($productFee > 0)
    <h3 class="font-semibold">Silahkan Pilih Metode</h3>
    <div class="space-y-4 mb-4">
      @forelse ($channels as $channel)
      @if ($channel['group'] == 'Virtual Account' || ($channel['name'] == 'QRIS' || $channel['name'] == 'QRIS2'))
      @else
      @continue
      @endif
      <button wire:click.prevent="selectPayment('{{$channel['code']}}')" class="flex w-full items-center justify-between border rounded p-2 cursor-pointer
      @if($selectedPayment == $channel['code'])
      ring-2 ring-primary font-semibold text-primary shadow
      @else
      hover:ring-2 hover:ring-primary hover:font-semibold hover:text-primary hover:shadow
      @endif
      ">
        <div class="flex items-center space-x-4">
          <img src="{{$channel['icon_url']}}" alt="" srcset="" class="size-[32px] object-contain rounded">
          <div class="text-left">
            <div class="font-semibold">{{$channel['name']}}</div>
            @if ($productFee > 450000 && $channel['group'] == 'Virtual Account')
            <div class="text-xs text-red-600 font-normal">*Paling Murah</div>
            @elseif($productFee <= 350000 && ($channel['name'] == 'QRIS' || $channel['name'] == 'QRIS2'))
            <div class="text-xs text-red-600 font-normal">*Paling Murah</div>
            @endif
          </div>
        </div>
        @if ($channel['code'] === $selectedPayment)
        <div><input type="checkbox" class="rounded-full border border-primary text-primary" checked /></div>
        @endif
      </button>
      @empty
      <div role="status">
        <svg aria-hidden="true" class="size-5 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
        </svg>
        <span class="sr-only">Loading...</span>
      </div>
      @endforelse
    </div>
    @endif
    @if ($platformFee > 0 || $taxFee > 0)
    <div class="border-l-4 border-l-gray-400 pl-2">
      @if ($platformFee > 0)
      <div class="flex items-center justify-between">
        <div class="relative group">
          <button type="button" class="flex items-center space-x-2">
            <div class="">Biaya Jasa Aplikasi</div>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
              <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
            </svg>
          </button>
          <div class="absolute hidden group-hover:block bottom-[130%] left-0 w-[300px] z-10 px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 opacity-1 bg-gray-900 rounded-lg shadow-sm">
            Jumlahnya disesuaikan metode pembayaran yang kamu pilih.😊
            <div class="tooltip-arrow" data-popper-arrow></div>
          </div>
        </div>
        <p class="text-black">Rp. {{number_format($platformFee,0,',','.')}}</p>
      </div>
      @endif
      @if ($taxFee > 0)
      <div class="flex items-center justify-between">
        <button data-tooltip-target="tooltip-platform-fee" type="button" class="flex items-center space-x-2">
          <div>Biaya Layanan</div>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
          </svg>
        </button>
        <div id="tooltip-platform-fee" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
          {{-- Silahkan menggunakan metode pembayaran wallet untuk menghilangkan biaya ini! --}}
          Jasa pembayaran third party.
          <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
        <p class="text-black">Rp. {{number_format($taxFee,0,',','.')}}</p>
      </div>
      @endif
    </div>
    @endif
    {{-- <label class="text-sm text-gray-500">User ID</label>
    <input type="text" placeholder="Contoh: 12345678" class="w-full p-2 border rounded mb-2"> --}}

    <h2 class="text-xl font-bold text-orange-500">Rp{{$totalPayment > 0 ? number_format($totalPayment,0,',','.'):'-'}}</h2>
    <div class="space-y-2">
      <button class="w-full py-2 @if(($totalPayment !== null && $totalPayment > 0) && $address) bg-primary @else bg-primary/50 cursor-default @endif text-white rounded" wire:loading.remove wire:key="pay" wire:target="pay" wire:click="pay">Bayar Sekarang</button>
      <button class="w-full py-2 bg-primary/50 cursor-default text-white rounded" wire:loading wire:key="pay" wire:target="pay">
        <div class="w-full flex items-center justify-center">
          @include('components.spinner')
        </div>
      </button>
      <p class="text-xs text-gray-500 text-center">
        ✅ 100% Transaksi Aman Dijamin oleh <span class="text-blue-500">Trade Guard</span>
      </p>
    </div>
  </div>
</div>
