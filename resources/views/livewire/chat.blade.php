<!-- component -->
<div class="flex max-sm:flex-col sm:overflow-hidden sm:rounded-lg sm:shadow" style="height: calc(100vh - 150px)">
  <!-- Sidebar -->
  <div class="w-full sm:w-[450px] bg-white sm:border-r sm:border-gray-300 @if($user || $session) max-sm:hidden @endif">
    <!-- Sidebar Header -->
    <header class="h-[37px] sm:h-[54px] px-4 flex items-center border-b border-gray-300 flex justify-between items-center bg-primary text-white">
      <h1 class="font-semibold text-white max-sm:text-sm">Chats</h1>
    </header>

    <livewire:components.chat.contact>

  </div>

  <!-- Main Chat Area -->
  <div class="w-full relative @if(!$user && !$session) max-sm:hidden @endif">
    <!-- Chat Header -->
    <header class="bg-white text-gray-700 h-[37px] sm:h-[54px] flex justify-between items-center px-4">
      <div class="flex items-center">
        <button class="sm:hidden text-xs pr-4" type="button" @click="window.history.back()">
          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-arrow-left size-5 font-bold" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
          </svg>
        </button>
        @if ($user)
        <div class="flex items-center space-x-2">
          <img src="{{$isStore ? storeProfile($user->store): profile($user)}}" alt="" srcset="" class="size-[24px] rounded-full">
          <h1 class="font-semibold">{{$isStore ? $user->store->name: $user->name}}</h1>
        </div>
        @else
        <h1 class="font-semibold">Jendela Pesan</h1>
        @endif
      </div>
      <button class="flex items-center justify-center rounded bg-primary text-white px-3 py-1 sm:px-4 sm:py-2 space-x-1" @click="Livewire.navigate('{{url('/')}}')">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-[14px]" viewBox="0 0 16 16">
          <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
        </svg>
        <div>Home</div>
      </button>
    </header>

    @if ($user)
    <!-- Chat Messages -->
    <livewire:components.chat.message-body :user="$user" :session="$session">
    <!-- Chat Input -->
    <livewire:components.chat.input :isStore="$isStore" :user="$user" :session="$session">
    @else
    <div class="flex items-center justify-center p-3" style="height: calc(100vh - 150px - 64px - 54px)">
      <div class="flex flex-col items-center">
        <img src="{{url('assets/images/speech-bubble.png')}}" alt="" class="w-[100px] h-auto mb-4" srcset="">
        <div class="font-semibold">Selamat Datang di Fitur Chat</div>
        <div class="">Balas Chat Sekarang</div>
      </div>
    </div>
    @endif
  </div>
</div>
<script>
// JavaScript for showing/hiding the menu
const menuButton = document.getElementById('menuButton');
const menuDropdown = document.getElementById('menuDropdown');

menuButton.addEventListener('click', () => {
  if (menuDropdown.classList.contains('hidden')) {
    menuDropdown.classList.remove('hidden');
  } else {
    menuDropdown.classList.add('hidden');
  }
});

// Close the menu if you click outside of it
document.addEventListener('click', (e) => {
  if (!menuDropdown.contains(e.target) && !menuButton.contains(e.target)) {
    menuDropdown.classList.add('hidden');
  }
});
</script>
