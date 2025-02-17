<!-- component -->
<div class="flex overflow-hidden rounded-lg shadow" style="height: calc(100vh - 150px)">
  <!-- Sidebar -->
  <div class="w-[450px] bg-white border-r border-gray-300">
    <!-- Sidebar Header -->
    <header class="p-4 border-b border-gray-300 flex justify-between items-center bg-primary text-white">
      <h1 class="text-2xl font-semibold text-white">Chats</h1>
    </header>


    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
      <ul class="flex flex-wrap -mb-px">
          <li class="me-2">
              <a href="#" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Member</a>
          </li>
          <li class="me-2">
              <a href="#" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">Toko</a>
          </li>
      </ul>
    </div>

    <!-- Contact List -->
    <div class="overflow-y-auto p-3 mb-9 pb-20" style="height: calc(100vh - 150px - 64px - 54px)">
      <livewire:components.chat.contact>
    </div>
  </div>

  <!-- Main Chat Area -->
  <div class="w-full relative">
      <!-- Chat Header -->
      <header class="bg-white p-4 text-gray-700">
          <h1 class="text-2xl font-semibold">Alice</h1>
      </header>

      <!-- Chat Messages -->
      <livewire:components.chat.message-body>

      <!-- Chat Input -->
      <livewire:components.chat.input>
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
