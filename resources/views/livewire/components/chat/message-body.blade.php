<div class="flex flex-col h-[calc(100vh-47px-37px-102px)] sm:h-[calc(100vh-150px-64px-66px)] overflow-hidden" wire:poll.4s>
  <div class="overflow-y-auto flex-1 p-4 pb-4 flex flex-col-reverse">

    <div class="flex flex-col">
      @foreach ($list as $row)
        @if ($row->sender_id != auth()->id())
          <div>
            @if ($row->reply_id !== null)
            <livewire:components.chat.reply :key="'reply-'.$row->id" :chat="$row" />
            @endif
            <!-- Incoming Message -->
            <div class="flex mb-4 cursor-pointer">
              <div class="w-9 h-9 rounded-full flex items-center justify-center mr-2">
                <img src="https://placehold.co/200x/ffa8e4/ffffff.svg?text=ʕ•́ᴥ•̀ʔ&font=Lato" alt="User Avatar" class="w-8 h-8 rounded-full">
              </div>
              <div class="flex flex-col bg-white py-2 px-3 gap-3 rounded-tr-[8px] rounded-b-[8px] relative">
                <p class="text-black ml-2">{{$row->text}}</p>
                <div class="text-xs self-start">{{\Carbon\Carbon::parse($row->created_at)->diffForHumans()}}</div>
                <span class="absolute top-0 -left-[0.4rem] w-0 h-0 border-l-8 border-l-transparent border-b-8 border-b-white border-r-8 border-r-transparent rotate-180"></span>
              </div>
            </div>
          </div>
        @else
        <!-- Outgoing Message -->
        <div class="flex flex-col items-end">
          @if ($row->reply_id !== null)
          <livewire:components.chat.reply :key="'reply-'.$row->id" :chat="$row" />
          @endif
          <div class="flex justify-end mb-4 relative">
            <div class="flex flex-col bg-gray-200 text-black rounded-tl-[8px] rounded-b-[8px] py-2 px-3 border">
              <p class="mr-2">{{$row->text}}</p>
              <div class="text-xs self-end text-gray-600">{{\Carbon\Carbon::parse($row->created_at)->diffForHumans()}}</div>
            </div>
            <span class="absolute top-[0px] -right-[0.4rem] w-0 h-0 border-l-8 border-l-transparent border-b-8 border-b-gray-200 border-r-8 border-r-transparent rotate-180"></span>
          </div>
        </div>
        @endif
      @endforeach
    </div>

  </div>
  @if ($tx)
  @php
  $firstDetail = $tx->details()->first();
  @endphp
  <div class="py-2 px-4 border-t bg-white flex items-center justify-between">
    <div class="flex items-center space-x-2">
      <img src="{{productImage($firstDetail->product->mainImage())}}" alt="" srcset="" class="size-9" />
      <div>
        <div class="text-xs font-semibold {{$tx->getStatusColor()}}">{{$tx->getStatusText()}}</div>
        <div class="text-xs text-black">INV/{{$tx->id}}</div>
      </div>
    </div>
    <button class="bg-gray-100 size-5 flex items-center justify-center" type="button" @click="Livewire.navigate('{{url()->current()}}')">
      <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-4" viewBox="0 0 16 16">
        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
      </svg>
    </button>
  </div>
  @endif
</div>
