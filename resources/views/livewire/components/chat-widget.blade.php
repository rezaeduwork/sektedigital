<div>
  @if ($open)
  <div class="fixed bottom-0 right-0 z-50 bg-white shadow-lg border rounded-t-lg animate-chatenter">
    <div class="flex items-center justify-between px-4 py-2 border-b">
      <div class="text-lg font-bold text-black">Chat ( 12)</div>
      <div>
        <button class="rounded-full p-2 bg-gray-100">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077 1.41-.513m14.095-5.13 1.41-.513M5.106 17.785l1.15-.964m11.49-9.642 1.149-.964M7.501 19.795l.75-1.3m7.5-12.99.75-1.3m-6.063 16.658.26-1.477m2.605-14.772.26-1.477m0 17.726-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205 12 12m6.894 5.785-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" />
          </svg>
        </button>
        <button class="rounded-full p-2 bg-gray-100" @click="$wire.set('open', false)">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
    <div class="flex h-[calc(502px-53px)]">
      <!-- Sidebar -->
      <div class="w-[223px] bg-white border-r border-gray-300 shrink-0">
        <!-- Contact List -->
        <div class="overflow-y-auto p-3 mb-9 pb-20">
          <div class="flex items-start mb-4 cursor-pointer hover:bg-gray-100 p-2 rounded-md">
            <div class="w-[32px] h-[32px] bg-gray-300 rounded-full mr-2 shrink-0 mt-[3px]">
              <img src="https://placehold.co/200x/ffa8e4/ffffff.svg?text=ʕ•́ᴥ•̀ʔ&font=Lato" alt="User Avatar"
                class="w-[32px] h-[32px] rounded-full">
            </div>
            <div class="flex-1">
              <h2 class="flex items-center justify-between">
                <div class="font-semibold">Alice</div>
                <div class="text-xs text-gray-500">{{ str_replace(' yang lalu', '', now()->diffForHumans()) }}</div>
              </h2>
              <p class="text-gray-600 text-sm">Hoorayy!!</p>
            </div>
          </div>
        </div>
      </div>
      <div class="w-full flex items-center justify-center bg-gray-100">
        <div class="text-black">Silahkan Pilih Kontak Disamping</div>
      </div>
      <!-- Main Chat Area -->
      <div class="w-full flex flex-col hidden">
        <!-- Chat Header -->
        <header class="bg-white p-4 text-gray-700">
          <h1 class="text-2xl font-semibold">Alice</h1>
        </header>

        <!-- Chat Messages -->
        <div class="overflow-y-auto p-4 pb-36 max-h-full">
          <!-- Incoming Message -->
          <div class="flex mb-4 cursor-pointer">
            <div class="w-9 h-9 rounded-full flex items-center justify-center mr-2">
              <img src="https://placehold.co/200x/ffa8e4/ffffff.svg?text=ʕ•́ᴥ•̀ʔ&font=Lato" alt="User Avatar"
                class="w-8 h-8 rounded-full">
            </div>
            <div class="flex max-w-96 bg-gray-100 rounded-lg p-3 gap-3">
              <p class="text-gray-700">Hey Bob, how's it going?</p>
            </div>
          </div>

          <!-- Outgoing Message -->
          <div class="flex justify-end mb-4 cursor-pointer">
            <div class="flex max-w-96 bg-primary text-white rounded-lg p-3 gap-3">
              <p>Hi Alice! I'm good, just finished a great book. How about you?</p>
            </div>
            <div class="w-9 h-9 rounded-full flex items-center justify-center ml-2">
              <img src="https://placehold.co/200x/b7a8ff/ffffff.svg?text=ʕ•́ᴥ•̀ʔ&font=Lato" alt="My Avatar"
                class="w-8 h-8 rounded-full">
            </div>
          </div>

        </div>

        <!-- Chat Input -->
        <div class="bg-white border-t border-gray-300 p-4 w-full">
          <div class="flex items-center">
            <input type="text" placeholder="Type a message..."
              class="w-full p-2 rounded-md border border-gray-400 focus:outline-none focus:border-blue-500">
            <button class="bg-primary text-white px-4 py-2 rounded-md ml-2">Send</button>
          </div>
        </div>
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

  </div>

  @endif
</div>
