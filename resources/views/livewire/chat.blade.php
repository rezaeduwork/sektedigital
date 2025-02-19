<!-- component -->
<div class="flex overflow-hidden rounded-lg shadow" style="height: calc(100vh - 150px)">
  <!-- Sidebar -->
  <div class="w-[450px] bg-white border-r border-gray-300">
    <!-- Sidebar Header -->
    <header class="p-4 border-b border-gray-300 flex justify-between items-center bg-primary text-white">
      <h1 class="text-2xl font-semibold text-white">Chats</h1>
    </header>

    <livewire:components.chat.contact>

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
