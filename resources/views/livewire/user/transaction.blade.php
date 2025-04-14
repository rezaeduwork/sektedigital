<div class="flex max-sm:flex-col mb-5 container max-sm:mt-[4rem] sm:space-x-4">
  <div class="flex shrink-0 w-full sm:w-[280px] py-2 sm:px-4 sm:bg-white sm:rounded-lg sm:shadow sm:px-4 sm:pb-4">
    <livewire:components.profile-sidebar>
  </div>
  <div class="w-full">
    <div class="space-y-2 sm:space-y-4">
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
      $unpaidTxQuery = auth()->user()->payments()->whereStatus('pending')->where(function($query) {
        $query->has('transactions')->orWhereHas('singleTransaction', function($query) {
          $query->whereNotNull('product_id');
        });
      });
      @endphp
      <div class="">
        <div class="rounded bg-white shadow max-sm:border-b rounded-lg sm:shadow">
          <div
            class="font-300 text-center text-black border-b border-gray-200 text-lg">
            <ul class="flex sm:px-2 max-w-full overflow-x-auto max-sm:sticky max-sm:top-[47px]">
              @foreach ($tabs as $tab)
              <li class="me-2 shrink-0">
                <a href="#"
                  @click="$wire.set('activeTab', '{{$tab[1]}}')"
                  class="
                  inline-block p-2 sm:p-4
                  @if($activeTab == $tab[1])
                  text-primary border-primary border-b-2
                  @else
                  hover:text-primary
                  @endif
                  rounded-t-lg
                  text-xs sm:text-sm
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
              class="bg-gray-50 border border-gray-300 text-gray-900 text-xs placeholder:text-xs sm:placeholder:text-sm sm:text-sm rounded-lg focus:ring-violet-300 focus:border-violet-300 block w-full p-1.5 sm:p-2.5"
              placeholder="Cari No. Pesanan / Nama Produk" />
            </div>
          </div>
        </div>
      </div>
      <div class="mb-2 space-y-2 sm:mb-4 sm:space-y-4">
        @if ($activeTab == 'unprocessed')
          @php
          $payments = $unpaidTxQuery->get();
          @endphp
          @forelse ($payments as $row)
          <div class="space-y-4 bg-white shadow rounded-lg border p-4" wire:key="{{'payment-'.$row->id}}">
            @if ($row->singleTransaction)
            @php
            $tx = $row->singleTransaction;
            $product = $row->singleTransaction->product;
            @endphp
            <div class="space-y-4">
              <div class="flex items-center justify-between w-full">
                <div class="flex items-center space-x-1">
                  <span>NO. PESANAN #{{$tx->id}}</span>
                  <span class="{{$tx->getStatusColor()}} font-semibold">{{$tx->getStatusText()}}</span>
                </div>
                <div class="flex items-center space-x-2 {{$tx->getStatusColor()}} font-semibold">
                  <div class="text-xs text-gray-600 flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-3 mt-[2px]" viewBox="0 0 16 16">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"></path>
                    </svg>
                    <span>{{\Carbon\Carbon::parse($tx->created_at)->format('Y-m-d H:i')}}</span>
                  </div>
                </div>
              </div>
              <div class="flex items-center justify-between space-x-5">
                <div class="w-full">
                  <div class="flex flex-row gap-5">
                    <img src="{{ productInstantImage($product) }}" alt="Ecommerce" class="w-16 h-16 rounded shrink-0">
                    <div class="flex flex-col gap-2">
                      <div class="space-y-2">
                        <!-- title -->
                        <a href="#" class="text-inherit">
                          <a class="font-black text-lg text-link" href="{{url($product->slug)}}" wire:navigate>{{ $product->title }}</a>
                        </a>
                        <span class="text-gray-500 text-sm flex items-center space-x-1">
                          {{-- <img src="{{ categoryImage($product->category) }}" alt="" srcset="" class="size-4"> --}}
                          <span>{{ $product->category }}</span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- price -->
                <div class="text-right shrink-0 space-y-4 text-2xl font-bold text-primary">
                  <div>Rp{{number_format($row->amount,0,',','.')}}</div>
                  {{-- <span class="text-xs font-medium me-2 px-2.5 py-0.5 rounded">{{$detail->quantity}} x Rp{{number_format($detail->price,0,',','.')}}</span> --}}
                </div>
              </div>
            </div>
            @else
            @foreach ($row->transactions as $rowTx)
            <livewire:components.user.transaction.item :key="uniqid().time()" :tx="$rowTx" :status="$activeTab">
            @endforeach
            @endif
            @if ($row->status === 'pending' && \Carbon\Carbon::parse($row->expired_at)->lt(now()))
            @php
            expirePayment($row);
            @endphp
            @endif
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
          <div class="text-center max-sm:text-xs">
            Belum ada transaksi.
          </div>
          @endforelse
        @else
          @forelse ($transactions as $row)
          <div class="space-y-4 bg-white shadow rounded-lg border p-4" wire:key="{{'tx-'.$row->id}}">
            <livewire:components.user.transaction.item :key="uniqid().time()" :tx="$row" :key="'tx-item-'.$row->id" :status="$activeTab">
          </div>
          @empty
          <div class="text-center max-sm:text-xs">
            Belum ada transaksi.
          </div>
          @endforelse
        @endif
      </div>
    </div>
  </div>
  @include('components.scripts.countdown')
</div>
