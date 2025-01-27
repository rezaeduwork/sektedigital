<div class="flex mb-5 container">
  <div class="flex shrink-0 w-[250px] py-2 px-4">
    <livewire:components.profile-sidebar>
  </div>
  <div class="w-full bg-white shadow rounded">
    <div>
      @php
      $tabs = [
        ['Semua',null],
        ['Belum Bayar','unprocessed'],
        ['Menunggu Konfirmasi','confirmed'],
        ['Proses','processed'],
        ['Menunggu Diselesaikan','store_finished'],
        ['Di Komplain','complain'],
        ['Selesai','finished'],
        ['Dibatalkan','cancelled'],
      ];
      $unpaidTxQuery = auth()->user()->payments()->whereStatus('pending');
      @endphp
      <div class="mb-4">
        <div class="table-responsive-xl rounded bg-white">
          <div
            class="font-300 text-center text-black border-b border-gray-200 text-lg">
            <ul class="block md:flex px-2 max-w-full overflow-x-auto">
              @foreach ($tabs as $tab)
              <li class="me-2 shrink-0">
                <a href="#"
                  @click="$wire.set('activeTab', '{{$tab[1]}}')"
                  class="
                  inline-block p-4
                  @if($activeTab == $tab[1])
                  text-primary border-primary border-b-2
                  @else
                  hover:text-primary
                  @endif
                  rounded-t-lg
                  text-sm
                  "
                  aria-current="page">{{$tab[0]}} ({{$tab[1] != 'unprocessed' ? queryListUserTransaction($tab[1])->count():$unpaidTxQuery->count() }})</a>
              </li>
              @endforeach
            </ul>
          </div>
          @if (!$activeTab)
            @if ($unpaidTxQuery->first())
            <div class="px-4 pt-4">
              <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 flex items-center justify-between w-full space-x-2" role="alert">
                <div class="">Ada {{$unpaidTxQuery->count()}} pembayaran pending nih!</div>
                <button class="font-semibold" @click="$wire.set('activeTab', 'unprocessed')">Bayar Sekarang</button>
              </div>
            </div>
            @endif
          @endif
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
        @if ($activeTab == 'unprocessed')
          @php
          $payments = $unpaidTxQuery->get();
          @endphp
          @forelse ($payments as $row)
          <div class="space-y-4 border-b pb-4">
            @foreach ($row->transactions as $rowTx)
            <livewire:components.user.transaction.item :key="uniqid().time()" :tx="$rowTx" :status="$activeTab">
            @endforeach
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flex items-center space-x-1" role="alert">
              <span class="block sm:inline">Bayar dalam</span>
              <div class="block sm:inline"
              x-data="countdown('{{checkPayment($row)['expired_time']}}')"
              x-init="startCountdown()"
              x-text="timeLeft"></div>
            </div>
            <div class="flex items-center justify-end w-full space-x-5">
              <div class="flex items-center space-x-4 shrink-0">
                <a class="bg-primary text-white font-semibold px-5 py-2 shrink-0 rounded" href="{{url('payment/'.$row->id)}}" wire:navigate>Bayar Sekarang</a>
              </div>
            </div>
          </div>
          @empty
          <div class="text-center">
            Belum ada transaksi.
          </div>
          @endforelse
        @else
          @forelse ($transactions as $row)
          <livewire:components.user.transaction.item :key="uniqid().time()" :tx="$row" :status="$activeTab">
          @empty
          <div class="text-center">
            Belum ada transaksi.
          </div>
          @endforelse
        @endif
      </div>
    </div>
  </div>
  @include('components.scripts.countdown')
</div>
