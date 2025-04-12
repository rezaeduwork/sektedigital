<footer class="bg-white border-t border-gray-300 p-2 sm:p-4 fixed sm:absolute bottom-[51px] sm:bottom-0 w-full">
  <div class="flex items-stretch sm:items-center">
    <input type="text" x-on:keyup.enter="$wire.send()" wire:model.live.debounce.250ms="text" placeholder="Tulis Pesan" class="w-full p-2 max-sm:text-xs max-sm:placeholder:text-xs rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary">
    <button @click="$wire.send()" class="bg-primary px-3 py-1 text-white sm:px-4 sm:py-2 rounded-lg ml-2 max-sm:text-xs">Send</button>
  </div>
</footer>
