<div>
  @if (!auth()->user()->store)
  <div class="mb-5">
    <h4 class="text-lg font-semibold">Buat Kios</h4>
    <p>
      <span class="font-semibold">Informasi!</span> Silahkan buat kios anda sendiri untuk mulai berjualan.
    </p>
  </div>
  <div class="mb-5">
    <div class="space-y-2" x-data>
      <label for="name" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Foto Kios</label>
      @if ($photo && $photo->temporaryUrl())
      <img src="{{$photo ? $photo->temporaryUrl(): url('storage/'.auth()->user()->photo)}}" alt="" class="w-[256px] h-[256px] border object-contain object-center">
      @endif
      <input wire:model.live="photo" class="hidden" type="file" accept="image/*" id="" x-ref="photo" />
      <button type="button" class="px-4 py-2 border hover:bg-primary hover:text-white" @click="$refs.photo.click()">
        Pilih Gambar
      </button>
      {{-- <div wire:loading wire:target="photo" wire:key="photo" class="text-green-600">Sedang Mengupload...</div> --}}
      <div
          x-data="{ uploading: false, progress: 0 }"
          x-on:livewire-upload-start="uploading = true"
          x-on:livewire-upload-finish="uploading = false"
          x-on:livewire-upload-cancel="uploading = false"
          x-on:livewire-upload-error="uploading = false"
          x-on:livewire-upload-progress="progress = $event.detail.progress"
          x-show="uploading"
          style="display: none;"
          class="w-full"
      >
          <!-- Progress Bar -->
          <div>
            <div class="w-full bg-gray-200 rounded-full">
              <div class="bg-primary text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" :style="{width: (progress+'%')}" x-text="progress+'%'"></div>
            </div>
          </div>
      </div>
      <ul class="text-gray-500">
        <li>Ukuran gambar: maks. 1 MB</li>
        <li>Format gambar: .JPEG, .PNG</li>
      </ul>
    </div>
    @error('photo') <span class="text-primary font-semibold">{{ $message }}</span> @enderror

  </div>
  <div class="mb-5">
    <label for="name" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Nama</label>
    <input type="text" id="name" wire:model.live.debounce.250ms="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5" placeholder="" required />
    @error('name')
    <small class="text-primary">{{$message}}</small>
    @enderror
  </div>
  <button type="button" wire:click="store" class="text-white bg-primary hover:bg-primary focus:ring-4 focus:ring-red-300 font-semibold rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
    Simpan
  </button>
  @elseif(auth()->user()->store->status == 'unverified')
  <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
    <span class="font-medium">Sedang Tahap Verifikasi Admin!</span> mohon menunggu maksimal 3x24 jam.
  </div>
  @elseif(auth()->user()->store->status == 'inactive')
  <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
    <span class="font-medium">Kios anda sudah tidak aktif!</span> Silahkan hubungi admin untuk mengaktifkan kembali.
  </div>
  @else
  <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
    <span class="font-medium">Kios anda telah aktif!</span> silahkan masuk ke halaman kios untuk menambahkan produk. <a href="{{url('store')}}" class="underline text-[#0000EE]">Masuk Kios</a>
  </div>
  @endif
</div>
