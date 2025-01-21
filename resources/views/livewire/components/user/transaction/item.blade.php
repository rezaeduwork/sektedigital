<div class="space-y-4 @if($tx->status != 'unprocessed') border-b pb-4 @endif">
  @php
  $total = $tx->details()->count();
  @endphp
  <div class="flex items-center justify-between">
    <div class="flex items-center space-x-1">
      <span>NO. PESANAN #{{$tx->id}}</span>
      <span class="{{$tx->getStatusColor()}} font-semibold">{{$tx->status}}</span>
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
  @php
  $firstDetail = $tx->details()->orderBy('store_id')->first();
  @endphp
  @if ($firstDetail)
    @php
    $product = $firstDetail->product;
    @endphp
    <div class="w-full mb-2 flex justify-between items-center space-x-2">
      <span class="text-black font-semibold">
        {{$product->store->name}}
      </span>
      <div class="flex">
        <button class="flex items-center space-x-1 text-xs">
          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-3" viewBox="0 0 16 16">
            <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
            <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
          </svg>
          <span>Hubungi Penjual</span>
        </button>
      </div>
    </div>
    <livewire:components.user.transaction.item-detail :detail="$firstDetail" :key="'detail-'.$firstDetail->id">
  @endif

  @if ($total > 1)
    @if ($showOthers)
      @foreach ($tx->details()->orderBy('id')->skip(1)->take($total)->get() as $rowDetail)
      <livewire:components.user.transaction.item-detail :key="'detail'.$rowDetail->id" :detail="$rowDetail">
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

  @if ($tx->status == 'store_finished')
  <div class="flex items-center justify-end w-full space-x-5" x-data="{showComplainModal: false}" @alert-success.window="showComplainModal = false">
    <div class="flex items-center space-x-2 shrink-0">
      <button class="bg-red-600 text-white font-semibold px-5 py-2 shrink-0 rounded" @click="showComplainModal = true">Ajukan Komplain</button>
      @include('components.modals.user-complain')
      <div class="relative" x-data="{showConfirmation: false}">
        <button class="bg-green-600 text-white font-semibold px-5 py-2 shrink-0 rounded" @click="showConfirmation = true">Selesaikan Pesanan</button>
        <div x-show="showConfirmation" style="display: none;" class="absolute z-10 min-w-[216px] overflow-auto rounded border border-slate-200 bg-white p-4 shadow-lg shadow-sm bottom-[120%] right-0">
          <div class="mb-2">Yakin ingin menyelesaikan ?</div>
          <div class="flex items-center space-x-2">
            <button class="text-xs rounded p-2" @click="showConfirmation = false">Batal</button>
            <button class="text-xs rounded p-2 bg-green-600 text-white" @click="$wire.finish()">Ya, Lanjutkan</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  @if ($tx->status == 'finished')
    @php
    $log = $tx->logs()->latest()->first();
    @endphp
    @if ($log)
    <div class="font-semibold">{{$log->description}}</div>
    @endif
  @endif

</div>
