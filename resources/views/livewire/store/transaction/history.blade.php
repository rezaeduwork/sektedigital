<div>
  @php
  $tabs = [
    'Perlu Proses',
    'Menunggu Konfirmasi',
    'Pesanan Selesai',
    'Semua Pesanan',
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
    <div class="table-responsive-xl rounded border bg-white">
      <div
        class="font-300 text-center text-black border-b border-gray-200 text-lg">
        <ul class="block md:flex px-2">
          @foreach ($tabs as $tab)
          <li class="me-2 shrink-0">
            <a href="#"
              @click="$wire.set('page', '{{$tab}}')"
              class="
              inline-block p-4
              @if($page == $tab)
              text-primary border-primary border-b-4
              @else
              hover:text-primary
              @endif
              rounded-t-lg
              "
              aria-current="page">{{$tab}}</a>
          </li>
          @endforeach
        </ul>
      </div>
      <div class="p-4 grid grid-cols-12">
        <div class="col-span-6">
          <input type="text"
          id="name"
          wire:model.live.debounce.250ms="name"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-violet-300 focus:border-violet-300 block w-full p-2.5"
          placeholder="Cari No. Pesanan / Nama Produk" />
        </div>
      </div>
    </div>
    @if ($page == 'Perlu Proses')
    <div class="p-6">Perlu Proses</div>
    @elseif ($page == 'Menunggu Konfirmasi')
    <div class="p-6">Menunggu Konfirmasi</div>
    @elseif ($page == 'Pesanan Selesai')
    <div class="p-6">Pesanan Selesai</div>
    @elseif ($page == 'Semua Pesanan')
    <div class="p-6">Semua Pesanan</div>
    @endif
  </section>

</div>
