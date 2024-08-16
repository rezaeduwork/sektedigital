<section class="container" x-data="{ searchCategory: '' }">
  <div class="flex flex-col items-center justify-start md:flex-row w-full mb-5 space-x-4">
    <button type="button" @click="history.back()"
      class="flex items-center space-x-2 md:w-auto border bg-white font-semibold rounded-lg px-5 py-2.5">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
      </svg>
      <span>Kembali</span>
    </button>
    <h1 class="text-2xl font-bold mb-5 md:mb-0">
      <div>Ubah {{$product->title}}</div>
    </h1>
  </div>
  <div class="table-responsive-xl p-6 mb-6 mb-lg-0 space-y-4  bg-white rounded border">
    <h6 class="text-lg">Informasi Produk</h6>
    <hr />
    <div class="mb-5 relative">
      <label for="type" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Kategori
        Dagangan</label>

      <button id="dropdownSearchButton" data-dropdown-toggle="dropdownSearch" data-dropdown-placement="bottom"
        class="flex items-center justify-between bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5"
        type="button">
        <div>
          {{ $category_id ? \App\Models\CategoryProduct::find($category_id)->name ?? 'Kategori tidak ada' : '-- Pilih Kategori --' }}
        </div>
        <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
          viewBox="0 0 10 6">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="m1 1 4 4 4-4" />
        </svg>
      </button>

      <!-- Dropdown menu -->
      <div id="dropdownSearch" class="z-10 hidden bg-white rounded-lg shadow w-full dark:bg-gray-700">
        <div class="p-3">
          <label for="input-group-search" class="sr-only">Search</label>
          <div class="relative">
            <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
              <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
              </svg>
            </div>
            <input type="text" @input.debounce.250ms="searchCategory = $event.target.value;"
              class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Pencarian">
          </div>
        </div>
        <ul class="px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200"
          aria-labelledby="dropdownSearchButton">
          @foreach (\App\Models\CategoryProduct::all() as $row)
            <li class="inline-block p-2 bg-gray-50 cursor-pointer"
              x-show="!searchCategory || ('{{ $row->name }}').toLowerCase().includes(searchCategory.toLowerCase())"
              @click="$wire.set('category_id', {{ $row->id }})">
              {{ $row->name }}
            </li>
          @endforeach
        </ul>
      </div>

      @error('category_id')
        <small class="!text-primary">{{ $message }}</small>
      @enderror
    </div>
    <div class="mb-5">
      <label for="title" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Foto</label>
      <div class="w-full space-x-2 flex items-center" x-data>
        @if ($main_photo && $main_photo->temporaryUrl())
          <div class="relative flex items-center shrink-0">
            <img src="{{ $main_photo->temporaryUrl() }}" alt="" srcset="" class="w-[100px] h-[100px] shrink-0">
            <button type="button" class="absolute top-1 right-1 text-primary bg-white rounded-full p-1"
              @click="$wire.set('main_photo', null)">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
              </svg>
            </button>
          </div>
        @else
          <div class="inline-block">
            <input type="file" accept="image/*" id="" class="hidden" wire:model="main_photo"
              x-ref="mainFile">
            <div
              class="flex flex-col justify-center items-center w-[100px] h-[100px] space-y-2 border cursor-pointer hover:border-primary hover:text-primary"
              @click="$refs.mainFile.click()">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-7">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              <div class="text-xs">Foto Utama</div>
            </div>
          </div>
        @endif
        <div class="h-[80px] w-[3px] rounded-full bg-gray-400 inline-block"></div>
        @if (sizeof($additional_photo) > 0)
          <div class="max-w-full overflow-x-auto flex items-center">
            @foreach ($additional_photo as $row)
              <img src="{{ $row->temporaryUrl() }}" alt="" srcset="" class="w-[100px] h-[100px]">
            @endforeach
          </div>
          <button type="button" class="text-primary bg-white rounded-full p-1"
            @click="$wire.set('additional_photo', [])">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="size-4">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
          </button>
        @else
          <div class="inline-block">
            <input type="file" accept="image/*" id="" class="hidden" wire:model="additional_photo"
              x-ref="additionalFile" multiple>
            <div
              class="flex flex-col justify-center items-center w-[100px] h-[100px] space-y-2 border cursor-pointer hover:border-primary hover:text-primary"
              @click="$refs.additionalFile.click()">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-7">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              <div class="text-xs">Tambah Foto</div>
            </div>
          </div>
        @endif
      </div>
      <div class="text-green-600" wire:loading wire:target="main_photo" wire:key="main_photo">Sedang Mengupload</div>
      <div class="text-green-600" wire:loading wire:target="additional_photo" wire:key="additional_photo">Sedang Mengupload</div>
      <ul class="text-gray-500 my-2 text-xs list-disc list-inside">
        <li>Ukuran gambar: maks. 1 MB</li>
        <li>Format gambar: .JPEG, .PNG</li>
        <li class="font-bold">Kosongkan jika tidak ingin mengubah</li>
      </ul>
      @error('main_photo')
        <small class="!text-primary block">{{ $message }}</small>
      @enderror
    </div>
    <div class="mb-5">
      <label for="title" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Judul</label>
      <input type="text" id="title" wire:model.live.debounce.250ms="title"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5"
        placeholder="" required />
      <small class="block">*Beri judul yang sesuai dengan produk kamu.</small>
      @error('title')
        <small class="!text-primary block">{{ $message }}</small>
      @enderror
    </div>
    <div class="mb-5">
      <label for="description"
        class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Deskripsi</label>
      <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
        role="alert">
        Jelaskan produk kamu sedetail mungkin agar mudah dimengerti.
      </div>
      <textarea id="description" wire:model.live.debounce.250ms="description" rows="5"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5"
        placeholder=""></textarea>
      @error('description')
        <small class="!text-primary block">{{ $message }}</small>
      @enderror
    </div>
    <div class="mb-5">
      <label for="price"
        class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Harga</label>
      <input id="price" wire:model.live.debounce.250ms="price" min="0" max="1000000000"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5"
        placeholder="">
      <small class="block">*Maks harga Rp. 1,000,000,000</small>
      @error('price')
        <small class="!text-primary block">{{ $message }}</small>
      @enderror
    </div>
    <div class="mb-5">
      <label for="stock"
        class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Stok</label>
      <input id="stock" wire:model.live.debounce.250ms="stock" min="0" max="1000000000"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5"
        placeholder="">
      <small class="block">*Maks stock Rp. 1,000,000,000</small>
      @error('stock')
        <small class="!text-primary block">{{ $message }}</small>
      @enderror
    </div>
    <button type="button" wire:click="update"
      class="w-full md:w-auto text-white bg-primary hover:bg-primary focus:ring-4 focus:ring-red-300 font-semibold rounded-lg px-5 py-2.5 me-2 mb-2">
      <span wire:key="update" wire:target="update" wire:loading.remove>Simpan</span>
      <span wire:key="update" wire:target="update" wire:loading>@include('components.spinner')</span>
    </button>
  </div>
</section>
