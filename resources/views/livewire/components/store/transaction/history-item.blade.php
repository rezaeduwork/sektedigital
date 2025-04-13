<div class="space-y-4 border-b pb-4 mb-4" x-data="{confirmationProcess: false, confirmationCompleted: false, confirmationRefund: false, confirmationRefundCancel: false}" @close-confirmation.window="confirmationProcess = false; confirmationCompleted = false;confirmationRefund = false; confirmationRefundCancel = false;">
  @php
  $total = $tx->storeDetails()->count();
  @endphp
  <div class="flex max-sm:flex-col max-sm:space-y-2 sm:items-center justify-between">
    <div class="max-sm:flex max-sm:justify-between max-sm:items-center sm:space-y-2">
      <div class="font-bold">NO. PESANAN #{{$tx->id}}</div>
      <div class="text-xs text-gray-600 flex items-center space-x-1">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-3 mt-[2px]" viewBox="0 0 16 16">
          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
        </svg>
        <span>{{\Carbon\Carbon::parse($tx->created_at)->format('Y-m-d H:i')}}</span>
      </div>
    </div>
    <div class="max-sm:flex max-sm:justify-between justify-start max-sm:flex-row items-center">
      <div class="flex items-center space-x-2">
        <img src="{{profile($tx->user)}}" alt="" class="size-[28px] rounded-full" />
        <span class="text-sm text-gray-700">{{$tx->customer_name}}</span>
      </div>
      <div class="flex items-center sm:mt-2">
        <button class="flex items-center space-x-1 text-xs" @click="window.open('{{url('chat/new/member/'.$tx->user->id)}}?tx_id={{$tx->id}}')">
          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-3" viewBox="0 0 16 16">
            <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
            <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
          </svg>
          <span>Hubungi Pembeli</span>
        </button>
      </div>
    </div>
  </div>
  <hr />
  @php
  $firstDetail = $tx->storeDetails()->orderBy('id')->first();
  @endphp
  @if ($firstDetail)
  <livewire:components.store.transaction.history-item-detail :key="'item-detail'.$firstDetail->id" :detail="$firstDetail" :isSelected="in_array($firstDetail->id,$checkedIds)">
  @endif
  @if ($total > 1)
    @if ($showOthers)
    @foreach ($tx->storeDetails()->orderBy('id')->skip(1)->take($total)->get() as $rowDetail)
    <livewire:components.store.transaction.history-item-detail :key="'item-detail'.$rowDetail->id" :detail="$rowDetail" :isSelected="in_array($rowDetail->id,$checkedIds)">
    @endforeach
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
    <div class="font-bold text-black text-lg sm:text-2xl">Rp{{number_format($tx->amount,0,',','.')}}</div>
  </div>

  <div class="flex items-center justify-between w-full space-x-5 bg-gray-50 rounded py-2 px-2">
    <div class="flex items-center @if (in_array($status,['confirmed','processed'])) justify-end @else justify-start @endif space-x-4 shrink-0 relative w-full">
      {{-- <button class="text-gray-600 px-5">Detail Transaksi</button> --}}
      @if ($status == 'confirmed')
        <button
        class="
        bg-primary text-white
        font-semibold px-5 py-2 shrink-0 rounded text-sm"
        @click="confirmationProcess = true"
        >Proses Pesanan</button>
        <div x-show="confirmationProcess" style="display: none;" class="absolute z-10 min-w-[180px] overflow-auto rounded border border-slate-200 bg-white p-4 shadow-lg shadow-sm bottom-[120%] right-0">
          <div class="mb-2">Yakin ingin proses ?</div>
          <div class="flex items-center space-x-2">
            <button class="text-xs rounded p-2" @click="confirmationProcess = false">Batal</button>
            <button class="text-xs rounded p-2 bg-primary text-white" wire:click.prevent="processing()">Ya, Lanjutkan</button>
          </div>
        </div>
      @endif

      @if ($status == 'processed')
      <livewire:components.store.transaction.history-finish-button :tx="$tx">
      @endif

      @if ($status == 'store_finished')
      <div class="self-start">
        <div class="font-semibold mb-2">Menunggu Konfirmasi User</div>
        <div class="rounded bg-gray-100 p-4">
          <div class="font-bold text-sm sm:text-lg">Bukti Penyelesaian</div>
          <div>{{$tx->proof_text}}</div>
          <a href="{{url('storage/transaction_proof/'.$tx->proof_file)}}" target="_blank" class="text-link flex items-center space-x-2">
            <div class="break-all">{{$tx->proof_file}}</div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-box-arrow-up-right size-[16px] shrink-0" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5"/>
              <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0z"/>
            </svg>
          </a>
        </div>
      </div>
      @endif

      @if ($status == 'complain')
      <button
        class="
        bg-green-600 text-white
        font-semibold px-5 py-2 shrink-0 rounded text-sm"
        @click="confirmationRefundCancel = true"
      >Tolak Complain</button>
      <div x-show="confirmationRefundCancel" style="display: none;" class="absolute z-10 min-w-[230px] overflow-auto rounded border border-slate-200 bg-white p-4 shadow-lg shadow-sm bottom-[120%] right-0">
        <div class="mb-2">Yakin ingin tolak complain ?</div>
        <div class="flex items-center space-x-2">
          <button class="text-xs rounded p-2" @click="confirmationRefundCancel = false">Batal</button>
          <button class="text-xs rounded p-2 bg-primary text-white" wire:click.prevent="rejectComplain()">Ya, Tolak</button>
        </div>
      </div>
      <button
        class="
        bg-primary text-white
        font-semibold px-5 py-2 shrink-0 rounded text-sm"
        @click="confirmationRefund = true"
      >Refund</button>
      <div x-show="confirmationRefund" style="display: none;" class="absolute z-10 min-w-[230px] overflow-auto rounded border border-slate-200 bg-white p-4 shadow-lg shadow-sm bottom-[120%] right-0">
        <div class="mb-2">Yakin ingin refund ?</div>
        <div class="flex items-center space-x-2">
          <button class="text-xs rounded p-2" @click="confirmationRefund = false">Batal</button>
          <button class="text-xs rounded p-2 bg-primary text-white" wire:click.prevent="acceptComplain()">Ya, Refund</button>
        </div>
      </div>
      @endif

      @if ($status == 'cancelled')
        @php
        $log = $tx->logs()->where('activity', 'cancelled')->latest()->first();
        @endphp
        @if ($log)
        <div class="font-semibold text-red-600">{{$log->description}}</div>
        @endif
      @endif

      @if ($status == 'finished')
        @php
        $log = $tx->logs()->where('activity', 'finished')->latest()->first();
        @endphp
        @if ($log)
        <div class="font-semibold">{{$log->description}}</div>
        @endif
      @endif

    </div>
  </div>
</div>
