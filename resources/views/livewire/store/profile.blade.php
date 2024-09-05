<section class="container">
  <h1 class="text-2xl font-bold mb-5">Profil Kios</h1>
  <div class="table-responsive-xl p-6 mb-6 mb-lg-0 space-y-4  bg-white rounded border">
    <div class="mb-5">
      <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
        <span class="font-medium">Informasi!</span> Sesuaikan profil kiosmu semenarik mungkin agar pembeli tertarik mengunjungi.
      </div>
      <div class="space-y-2" x-data>
        <label for="name" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Foto Kios</label>
        @if ($photo && $photo->temporaryUrl() || (auth()->user()->store && auth()->user()->store->photo))
        <img src="{{$photo ? $photo->temporaryUrl(): ( auth()->user()->store->photo ? url('storage/'.auth()->user()->store->photo):'' )}}" alt="" class="w-full h-auto lg:w-[256px] lg:h-[256px] border object-contain object-center">
        @endif
        <input wire:model.live="photo" class="hidden" type="file" accept="image/*" id="" x-ref="photo" />
        <button type="button" class="px-4 py-2 border hover:bg-primary hover:text-white w-full lg:w-auto" @click="$refs.photo.click()">
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
      @error('photo') <span class="!text-primary font-semibold">{{ $message }}</span> @enderror
    </div>
    <div class="mb-5">
      <label for="name" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Nama Kios</label>
      <input type="text" id="name" wire:model.live.debounce.250ms="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-violet-300 focus:border-violet-300 block w-full p-2.5" placeholder="" required />
      @error('name')
      <small class="!text-primary">{{$message}}</small>
      @enderror
    </div>
    <div class="mb-2">
      <label for="name" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Deskripsi</label>
      <textarea id="description" wire:model.live.debounce.250ms="description" rows="5" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-violet-300 focus:border-violet-300 block w-full p-2.5" placeholder="" required></textarea>
      @error('description')
      <small class="!text-primary">{{$message}}</small>
      @enderror
    </div>
    <button type="button" wire:click="update" class="text-white bg-primary hover:bg-primary focus:ring-4 focus:ring-violet-300 font-semibold rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
      Simpan
    </button>
  </div>
</section>
