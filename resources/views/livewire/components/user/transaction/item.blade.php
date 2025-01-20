<div class="space-y-4 border-b pb-4">
  @php
  $total = $tx->details()->when($status, function($query) use ($status) {
    $query->whereStatus($status);
  })->count();
  @endphp
  <div class="flex items-center justify-between">
    <div>NO. PESANAN {{$tx->id}}</div>
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
  $firstDetail = $tx->details()->orderBy('store_id')->orderBy('store_id')->first();
  @endphp
  @if ($firstDetail)
    @php
    $product = $firstDetail->product;
    @endphp
    <livewire:components.user.transaction.item-detail :detail="$firstDetail" :key="'detail-'.$firstDetail->id">
  @endif

  @if ($total > 1)
    @if ($showOthers)
      @foreach ($tx->details()->when($status, function($query) use ($status) {
        $query->whereStatus($status);
      })->orderBy('id')->skip(1)->take($total)->get() as $rowDetail)
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

  <div class="flex items-center justify-end w-full space-x-5">
    <div class="flex items-center space-x-4 shrink-0">
      @if ($tx->status === 'finished')
      <button class="bg-primary text-white font-semibold px-5 py-2 shrink-0 rounded">Beli Lagi</button>
      @endif
      <button class="hover:underline px-5">Detail Transaksi</button>
    </div>
  </div>
</div>
