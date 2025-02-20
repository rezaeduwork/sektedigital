<div class="flex flex-col h-[calc(100vh-150px-64px-75px)] overflow-hidden">
  <div class="overflow-y-auto flex-1 p-4 pb-4 flex flex-col-reverse">

    <div class="flex flex-col">
      @foreach ($list as $row)
        @if ($row->sender_id != auth()->id())
        <!-- Incoming Message -->
        <div class="flex mb-4 cursor-pointer">
          <div class="w-9 h-9 rounded-full flex items-center justify-center mr-2">
            <img src="https://placehold.co/200x/ffa8e4/ffffff.svg?text=ʕ•́ᴥ•̀ʔ&font=Lato" alt="User Avatar" class="w-8 h-8 rounded-full">
          </div>
          <div class="flex flex-col bg-white py-2 px-3 gap-3 rounded-tr-[8px] rounded-b-[8px] relative">
            <p class="text-gray-700 ml-2">{{$row->text}}</p>
            <div class="text-xs self-start">{{\Carbon\Carbon::parse($row->created_at)->diffForHumans()}}</div>
            <span class="absolute top-0 -left-[0.4rem] w-0 h-0 border-l-8 border-l-transparent border-b-8 border-b-white border-r-8 border-r-transparent rotate-180"></span>
          </div>
        </div>
        @else
        <!-- Outgoing Message -->
        <div class="flex justify-end mb-4 relative">
          <div class="flex flex-col bg-primary text-white rounded-tl-[8px] rounded-b-[8px] py-2 px-3 gap-1 border">
            <p class="mr-2">{{$row->text}}</p>
            <div class="text-xs self-end">{{\Carbon\Carbon::parse($row->created_at)->diffForHumans()}}</div>
          </div>
          <span class="absolute top-[1px] -right-[0.4rem] w-0 h-0 border-l-8 border-l-transparent border-b-8 border-b-primary border-r-8 border-r-transparent rotate-180"></span>
        </div>
        @endif
      @endforeach
    </div>

  </div>
</div>
