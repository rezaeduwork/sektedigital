<div class="flex mb-5 container">
  <div class="flex shrink-0 w-[250px] py-2 px-4">
    <livewire:components.profile-sidebar>
  </div>
  <div class="w-full bg-white shadow rounded">
    <div>
      @php
      $tabs = [
        'Semua',
        'Belum Bayar',
        'Menunggu Konfirmasi',
        'Proses',
        'Selesai',
        'Dibatalkan',
      ];
      @endphp
      <div class="mb-4">
        <div class="table-responsive-xl rounded bg-white">
          <div
            class="font-300 text-center text-black border-b border-gray-200 text-lg">
            <ul class="block md:flex px-2 max-w-full overflow-x-auto">
              @foreach ($tabs as $tab)
              <li class="me-2 shrink-0">
                <a href="#"
                  @click="$wire.set('activeTab', '{{$tab}}')"
                  class="
                  inline-block p-4
                  @if($activeTab == $tab)
                  text-primary border-primary border-b-2
                  @else
                  hover:text-primary
                  @endif
                  rounded-t-lg
                  text-sm
                  "
                  aria-current="page">{{$tab}} ({{queryListUserTransaction($tab)->count()}})</a>
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
      </div>
      <div class="mb-4 px-4 space-y-4">
        @forelse ($transactions as $row)
        <livewire:components.user.transaction.item :key="uniqid().time()" :tx="$row">
        @empty
        <div class="text-center">
          Belum ada transaksi.
        </div>
        @endforelse
      </div>
    </div>
  </div>
</div>
