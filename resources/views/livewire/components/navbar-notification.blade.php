<div>
  @auth
  <div class="py-2 px-4 font-semibold text-gray-900 border-b">Notifications</div>

  <ul class="divide-y">
    @forelse (auth()->user()->notifications()->take(5)->get() as $row)
    <li class="p-4 @if(!$row->read_at) bg-gray-200 @endif hover:bg-gray-200 cursor-pointer" @click="$wire.read('{{$row->id}}');Livewire.navigate('{{$row->data['url'] ?? '#'}}')" :key="'notification-'.{{$row->id}}">
      <p class="text-xs text-gray-500">{{\Carbon\Carbon::parse($row->created_at)->diffForHumans()}}</p>
      <p class="font-semibold text-black">{{$row->data['title']}}</p>
      <p class="text-xs text-gray-900">{{$row->data['description']}}</p>
    </li>
    @empty
    <li class="p-4 text-center">Belum ada notifikasi.</li>
    @endforelse
  </ul>

  <div class="flex items-center justify-between py-2 border-t">
    <div class="py-2 px-4 text-center text-sm text-blue-500 cursor-pointer">Tandai Semua Dibaca</div>
    <div class="py-2 px-4 text-center text-sm text-blue-500 cursor-pointer">Lihat Semua</div>
  </div>
  @endauth
  @guest
  <h6 class="px-4 border-b py-2 mb-0">Notification</h6>
  <p class="mb-0 px-4 py-3">
    <a href="#" class="text-primary">Masuk</a>
    atau
    <a href="#" class="text-primary">Daftar</a>
    untuk melihat notifikasi!
  </p>
  @endguest
</div>
