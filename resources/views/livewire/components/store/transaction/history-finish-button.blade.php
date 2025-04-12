<div class="w-full">
  <div class="mb-4 font-bold">Selesaikan Pesanan</div>
  <textarea rows="2" wire:model.live="finishNote" class="resize-none w-full mb-4 block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300" placeholder="Tulis pesan penyelesaian"></textarea>
  @error('finishNote')
  <div class="mb-4 text-red-600 text-sm">*{{$message}}</div>
  @enderror
  <div class="mb-4">
    <input type="file" wire:model.live="finishFile" class="resize-none w-full block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300" />
    <div class="text-xs">Bukti bisa berupa gambar atau pdf</div>
  </div>
  @error('finishFile')
  <div class="mb-4 text-red-600 text-sm">*{{$message}}</div>
  @enderror
  <div class="relative">
    <button
    class="
    bg-primary text-white
    font-semibold px-5 py-2 shrink-0 rounded text-sm"
    @click="confirmationCompleted = true"
    >Selesai</button>
    <div x-show="confirmationCompleted" style="display: none;" class="absolute z-10 min-w-[230px] overflow-auto rounded border border-slate-200 bg-white p-4 shadow-lg shadow-sm bottom-[120%] left-0">
      <div class="mb-2">Yakin ingin menyelesaikan ?</div>
      <div class="flex items-center space-x-2">
        <button class="text-xs rounded p-2" @click="confirmationCompleted = false">Batal</button>
        <button class="text-xs rounded p-2 bg-primary text-white" wire:click.prevent="completing()">Ya, Lanjutkan</button>
      </div>
    </div>
  </div>
</div>
