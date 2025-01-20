<div>
  @php
  $tabs = [
    ['Perlu Proses','confirmed'],
    ['Di Proses','processed'],
    ['Menunggu Konfirmasi User','store_finished'],
    ['Pesanan Selesai','finished'],
    ['Semua Pesanan', null],
  ];
  @endphp
  <section class="container p-0">
    <h1 class="text-2xl font-bold mb-5 md:flex md:items-center md:justify-between">
      <div class="mb-5 w-full md:w-auto text-center md:text-left md:mb-0">Pesanan Kios</div>
      <button class="py-3 px-6 text-base font-normal w-full md:w-auto flex items-center justify-center space-x-2 text-white bg-primary">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
        </svg>
        <span>
          Unduh Riwayat Pesanan
        </span>
      </button>
    </h1>
    <div class="table-responsive-xl rounded border bg-white mb-4">
      <div
        class="font-300 text-center text-black border-b border-gray-200 text-lg">
        <ul class="block md:flex px-2">
          @foreach ($tabs as $tab)
            <li class="me-2 shrink-0">
              @php
              $totalTx = \App\Models\Transaction::query()->storeTransactionQuery($tab[1])->count();
              @endphp
              <a href="#"
                @click="$wire.set('page', '{{$tab[0]}}')"
                class="
                inline-block p-4
                @if($page == $tab[0])
                text-primary border-primary border-b-4
                @else
                hover:text-primary
                @endif
                rounded-t-lg
                "
                aria-current="page">{{$tab[0]}} ({{$totalTx}})</a>
            </li>
          @endforeach
        </ul>
      </div>
      <div class="p-4 grid grid-cols-12 border-b">
        <div class="col-span-6">
          <input type="text"
          id="name"
          wire:model.live.debounce.250ms="name"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-violet-300 focus:border-violet-300 block w-full p-2.5"
          placeholder="Cari No. Pesanan / Nama Produk" />
        </div>
      </div>

      @foreach ($tabs as $tab)
        @if ($page == $tab[0])
        <livewire:components.store.transaction.history-table :key="'tab-'.$tab[1]" :status="$tab[1]">
        @endif
      @endforeach

      {{-- <livewire:components.store.transaction.history-table :status="null"> --}}

    </div>
  </section>

</div>
