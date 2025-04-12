<div wire:poll.8s>
  <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px">
      <li class="me-2">
        <button class="inline-block p-2 sm:p-4 max-sm:text-xs @if(!$tab) text-blue-600 border-b-2 border-blue-600 @endif rounded-t-lg hover:text-blue-600 hover:border-b-2 hover:border-blue-600" @click="$wire.set('tab', null)">Semua</button>
      </li>
      <li class="me-2">
        <button class="inline-block p-2 sm:p-4 max-sm:text-xs @if($tab == 'member') text-blue-600 border-b-2 border-blue-600 @endif rounded-t-lg hover:text-gray-600 hover:text-blue-600 hover:border-b-2 hover:border-blue-600" @click="$wire.set('tab', 'member')">Member</button>
      </li>
      <li class="me-2">
        <button class="inline-block p-2 sm:p-4 max-sm:text-xs @if($tab == 'store') text-blue-600 border-b-2 border-blue-600 @endif rounded-t-lg hover:text-gray-600 hover:text-blue-600 hover:border-b-2 hover:border-blue-600" @click="$wire.set('tab', 'store')">Toko</button>
      </li>
    </ul>
  </div>
  <div class="overflow-y-auto p-2 sm:p-3 mb-5 pb-12 sm:mb-9 sm:pb-20 h-[calc(100vh-47px-102px)] sm:h-[calc(100vh-150px-64px-54px)]">
    @forelse ($list as $row)
    @php
    $isStore = true;
    if (auth()->id() == $row->user_store_id) {
      $isStore = false;
    }
    $lastChat = $row->chats()->latest()->first();
    $isRead = true;
    if ($lastChat->receiver_id == auth()->id() && $lastChat->read_at === null) {
      $isRead = false;
    }
    @endphp
    <a class="flex items-center mb-4 {{!$isRead ? 'bg-gray-100':''}} cursor-pointer hover:bg-gray-100 p-2 rounded-md" href="{{url('chat/'.($isStore ? $row->user_store_id: $row->user_id))}}" wire:navigate>
      <div class="size-10 bg-gray-300 rounded-full mr-3 flex items-center justify-center shrink-0">
        <img src="{{$isStore ? storeProfile($row->store): profile($row->member)}}" alt="User Avatar" class="size-8 rounded-full">
      </div>
      <div class="flex-1">
        <h2 class="@if(!$isRead) font-bold @else font-semibold @endif">{{$isStore ? $row->store->name: $row->member->name}}</h2>
        <p class="text-xs text-gray-500 @if(!$isRead) font-semibold @endif">{{\Str::limit($lastChat->text, 20, '...') ?? '...'}}</p>
      </div>
    </a>
    @empty
    <div>Belum ada pesan</div>
    @endforelse
  </div>
</div>
