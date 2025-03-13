<div class="px-2 py-1 bg-white rounded flex items-center gap-2 text-red-600 font-semibold text-xs" wire:poll.30s="getPrice">
  @if ($ticker !== null && $price > 0)
  Rate (1{{$ticker}} = Rp{{number_format($price,0,',','.')}})
  @else
  loading price...
  @endif
</div>
