<div class="space-y-6 border-b pb-4 mb-4" x-data="{confirmationProcess: false, confirmationCompleted: false}" @close-confirmation.window="confirmationProcess = false">
  @php
  $total = $tx->storeDetails()->whereStatus($status)->count();
  @endphp
  <div class="flex items-center justify-between">
    <div>
      <div>NO. PESANAN #{{$tx->id}}</div>
      <div class="text-xs text-gray-600 flex items-center space-x-1">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-3 mt-[2px]" viewBox="0 0 16 16">
          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
        </svg>
        <span>{{\Carbon\Carbon::parse($tx->created_at)->format('Y-m-d H:i')}}</span>
      </div>
    </div>
    <div class="flex items-center space-x-2">
      <img src="{{profile($tx->user)}}" alt="" class="size-[28px] rounded-full" />
      <span class="text-sm text-gray-700">{{$tx->customer_name}}</span>
    </div>
  </div>
  @php
  $firstDetail = $tx->storeDetails()->whereStatus($status)->orderBy('id')->first();
  @endphp
  @if ($firstDetail)
  <livewire:components.store.transaction.history-item-detail :key="'item-detail'.$firstDetail->id" :detail="$firstDetail" :isSelected="in_array($firstDetail->id,$checkedIds)">
  @endif
  @if ($total > 1)
    @if ($showOthers)
    <div class="mb-4">
      @foreach ($tx->storeDetails()->whereStatus($status)->orderBy('id')->skip(1)->take($total)->get() as $rowDetail)
      <livewire:components.store.transaction.history-item-detail :key="'item-detail'.$rowDetail->id" :detail="$rowDetail" :isSelected="in_array($rowDetail->id,$checkedIds)">
      @endforeach
    </div>
    @endif
    <button type="button" wire:click="toggleShowProduct">
      <span class="bg-green-100 text-green-800 text-xs font-medium me-2 text-center px-3.5 py-1.5 rounded flex items-center justify-center space-x-1">
        @if ($showOthers)
        <div>Sembunyikan Produk</div>
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-3 mt-[2px] text-green-800" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708z"/>
        </svg>
        @else
        <div>Lihat {{$total}} Produk Lainnya</div>
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-3 mt-[2px] text-green-800" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
        </svg>
        @endif
      </span>
    </button>
  @endif
  <div class="flex items-center justify-between">
    <div class="font-semibold text-sm">Total Harga</div>
    <div class="font-bold text-black text-2xl">Rp{{number_format($tx->amount,0,',','.')}}</div>
  </div>
  <div class="flex items-center justify-between w-full space-x-5 bg-gray-50 rounded px-4 py-2">
    <div class="flex">
      <button class="flex items-center space-x-1 text-xs">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-3" viewBox="0 0 16 16">
          <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
          <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
        </svg>
        <span>Hubungi Pembeli</span>
      </button>
    </div>
    <div class="flex items-center space-x-4 shrink-0 relative">
      {{-- <button class="text-gray-600 px-5">Detail Transaksi</button> --}}
      @if ($status == 'confirmed')
        <button
        class="
        @if(sizeof($checkedIds) <= 0)
        opacity-[0.3] cursor-default
        @endif
        bg-primary text-white
        font-semibold px-5 py-2 shrink-0 rounded text-sm"
        @if(sizeof($checkedIds) > 0)
        @click="confirmationProcess = true"
        @endif
        >Proses Pesanan</button>
        @if(sizeof($checkedIds) > 0)
        <div x-show="confirmationProcess" style="display: none;" class="absolute z-10 min-w-[180px] overflow-auto rounded border border-slate-200 bg-white p-4 shadow-lg shadow-sm bottom-[120%] right-0">
          <div class="mb-2">Yakin ingin proses ?</div>
          <div class="flex items-center space-x-2">
            <button class="text-xs rounded p-2" @click="confirmationProcess = false">Batal</button>
            <button class="text-xs rounded p-2 bg-primary text-white" wire:click.prevent="processing()">Ya, Lanjutkan</button>
          </div>
        </div>
        @endif
      @endif

      @if ($status == 'processed')
        <button
        class="
        @if(sizeof($checkedIds) <= 0)
        opacity-[0.3] cursor-default
        @endif
        bg-primary text-white
        font-semibold px-5 py-2 shrink-0 rounded text-sm"
        @if(sizeof($checkedIds) > 0)
        @click="confirmationCompleted = true"
        @endif
        >Selesai</button>
        @if(sizeof($checkedIds) > 0)
        <div x-show="confirmationCompleted" style="display: none;" class="absolute z-10 min-w-[230px] overflow-auto rounded border border-slate-200 bg-white p-4 shadow-lg shadow-sm bottom-[120%] right-0">
          <div class="mb-2">Yakin ingin menyelesaikan ?</div>
          <div class="flex items-center space-x-2">
            <button class="text-xs rounded p-2" @click="confirmationCompleted = false">Batal</button>
            <button class="text-xs rounded p-2 bg-primary text-white" wire:click.prevent="completing()">Ya, Lanjutkan</button>
          </div>
        </div>
        @endif
      @endif

      @if ($status == 'store_finished')
      <div class="font-semibold">Menunggu Konfirmasi User</div>
      @endif

    </div>
  </div>
</div>
