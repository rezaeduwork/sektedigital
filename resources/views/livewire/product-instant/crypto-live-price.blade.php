<div class="px-2 py-1 bg-violet-100 text-primary rounded flex items-center gap-2 font-semibold text-xs" wire:poll.30s="getPrice" x-init="$wire.getPrice()">
  @if ($ticker !== null && $price > 0)
  1{{$ticker}} = Rp{{number_format($price,0,',','.')}}
  @else
  loading price...
  @endif
</div>
