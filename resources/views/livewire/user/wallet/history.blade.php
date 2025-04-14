<div>
  <div class="flex space-x-2 mb-2 sm:mb-6">
    <div class="flex items-center space-x-2">
      <div class="flex items-center justify-center size-7 sm:size-8 rounded-full bg-red-200 text-red-600">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
          class="size-4 sm:size-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75 12 3m0 0 3.75 3.75M12 3v18" />
        </svg>
      </div>
      <div class="flex items-center justify-center size-7 sm:size-8 rounded-full bg-green-200 text-green-500">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
          stroke="currentColor" class="size-4 sm:size-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25 12 21m0 0-3.75-3.75M12 21V3" />
        </svg>
      </div>
    </div>
    <ul class="hidden text-sm font-medium text-center text-gray-500 rounded-lg sm:flex space-x-2 px-4">
      @php
        $tabs = [
          ['Semua', 'all'],
          ['Berhasil', 'success'],
          ['Pending', 'pending'],
          ['Gagal', 'reject'],
        ];
      @endphp
      @foreach ($tabs as $row)
        <li class="w-full focus-within:z-10">
          <button type="button" @click="$wire.set('activeTab', '{{$row[1]}}')"
            class="inline-block w-full px-4 py-2 text-gray-900 bg-gray-100 rounded-full outline-none @if ($activeTab == $row[1]) ring-2 ring-primary bg-violet-200 text-primary @else hover:ring-2 hover:ring-primary hover:bg-violet-200 hover:text-primary @endif"
            aria-current="page">{{ $row[0] }}</button>
        </li>
      @endforeach
    </ul>
    <div class="flex">
      <label for="searchProducts" class="invisible hidden">Search</label>
      <input
        wire:model.live.debounce.250s="search"
        class="border border-gray-300 text-gray-900 rounded-l-lg !ring-none !outline-none focus:border-gray-300 shadow-primary block p-2 px-3 disabled:opacity-50 disabled:pointer-events-none w-full text-xs placeholder:text-xs sm:text-sm sm:placeholder:text-sm"
        placeholder="Cari Riwayat">
      <button
        class="rounded-none rounded-r-lg btn text-xs sm:text-sm font-normal inline-flex items-center gap-x-2 bg-primary text-white border-primary disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-primary hover:border-primary active:bg-primary active:border-primary focus:outline-none focus:ring-4 focus:ring-violet-100"
        type="button">
        Cari
      </button>
    </div>
  </div>
  <hr class="my-4" />
  <div>
    @foreach ($list as $row)
      <div class="w-full">
        <div class="space-y-2">
          <div class="flex max-sm:flex-col sm:items-center max-sm:space-y-1 sm:space-x-4">
            @if ($row->amount < 0)
              <div class="flex items-center justify-center size-7 sm:size-8 rounded-full bg-red-200 text-red-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="size-4 sm:size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75 12 3m0 0 3.75 3.75M12 3v18" />
                </svg>
              </div>
            @else
              <div class="flex items-center justify-center size-7 sm:size-8 rounded-full bg-green-200 text-green-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="size-4 sm:size-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25 12 21m0 0-3.75-3.75M12 21V3" />
                </svg>
              </div>
            @endif
            <div class="w-full max-sm:space-y-1">
              <div class="flex items-start sm:items-center space-x-2">
                <div class="text-sm sm:text-lg font-bold">
                  @if ($row->name)
                    {{ $row->name }}
                  @else
                    @if ($row->type == 'coin')
                      Coin
                    @else
                      Fund
                    @endif
                  @endif
                </div>
                @if ($row->status == 'success')
                <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">Berhasil</span>
                @elseif($row->status == 'pending')
                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">Pending</span>
                @else
                <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">Gagal</span>
                @endif
              </div>
              <div>{{ $row->description }}</div>
              <div class="flex items-center space-x-2">
                <div class="text-gray-500 text-xs">
                  {{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('l, d F Y') }}</div>
                <div class="text-gray-300 text-xs">&bull;</div>
                <div class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($row->created_at)->format('H:i') }}</div>
              </div>
            </div>
            <div class="shrink-0 space-y-1">
              <div
              class="
              @if ($row->amount < 0) text-red-600
              @else
              text-green-700 @endif
              font-bold sm:text-lg sm:text-right">
                Rp {{ number_format($row->amount) }}</div>
              <div class="flex items-center space-x-2">
                @if ($row->type == 'coin')
                  <img src="{{ url('assets/images/coin.png') }}" alt="" srcset="" class="size-4 shrink-0">
                @elseif($row->type == 'store_fund')
                  <svg xmlns="http://www.w3.org/2000/svg" class="size-[16px] text-green-600" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z"/>
                  </svg>
                @else
                  <img src="{{ url('assets/images/wallet.png') }}" alt="" srcset=""
                    class="size-4 shrink-0">
                @endif
                <div class="max-sm:text-xs">
                  @if ($row->type == 'coin')
                    Coin
                  @elseif($row->type == 'store_fund')
                    Saldo Toko
                  @elseif($row->type == 'withdraw')
                    @if ($row->status == 'pending')
                    Request Penarikan
                    @else
                    Penarikan
                    @endif
                  @else
                    Saldo Pembeli
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr class="my-4" />
      </div>
    @endforeach
    {{ $list->links() }}
  </div>

</div>
