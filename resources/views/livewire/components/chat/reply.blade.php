<div class="flex">
  @php
  $row = $chat;
  @endphp
  @if ($row->reply_id && $row->reply_type == 'transaction')
  @php
  $firstDetail = $row->transaction->details()->first();
  @endphp
  <div class="flex items-center space-x-2 mb-2 bg-white p-2 rounded-lg border">
    <img src="{{productImage($firstDetail->product->mainImage())}}" alt="" srcset="" class="size-9 rounded-lg" />
    <div>
      <div class="text-xs font-semibold {{$row->transaction->getStatusColor()}}">{{$row->transaction->getStatusText()}}</div>
      <div class="text-xs text-black">INV/{{$row->transaction->id}}</div>
    </div>
  </div>
  @else

  @endif
</div>
