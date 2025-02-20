<footer class="bg-white border-t border-gray-300 p-4 absolute bottom-0 w-full">
  <div class="flex items-center">
      <input type="text" x-on:keyup.enter="$wire.send()" wire:model.live.debounce.250ms="text" placeholder="Tulis Pesan" class="w-full p-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary">
      <button @click="$wire.send()" class="bg-primary text-white px-4 py-2 rounded-lg ml-2">Send</button>
  </div>
</footer>
