<div class="w-full space-y-4">
  <div class="flex flex-col items-center space-y-4" x-data>
    <img src="{{$photo ? $photo->temporaryUrl(): url('storage/'.auth()->user()->photo)}}" alt="" class="w-[56px] h-[56px] rounded-full border object-contain object-center">
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
    @error('photo') <span class="text-primary font-semibold">{{ $message }}</span> @enderror
    <ul class="text-gray-500">
      <li>Ukuran gambar: maks. 1 MB</li>
      <li>Format gambar: .JPEG, .PNG</li>
    </ul>
  </div>
  <ul>
    @php
    $menus = [
      [
        'icon' => '
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-primary">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
          </svg>
        ',
        'name' => 'Akun Saya',
        'sub' => [
          [
            'name' => 'Profil',
            'url' => ''
          ]
        ]
      ],
      [
        'icon' => '
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-primary">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
          </svg>
        ',
        'name' => 'Pesanan Saya',
        'sub' => [
          [
            'name' => 'Semua',
          ],
          [
            'name' => 'Belum Bayar',
          ],
          [
            'name' => 'Di Proses',
          ],
          [
            'name' => 'Berhasil',
          ],
        ],
      ]
    ];
    @endphp
    @foreach ($menus as $row)
    @php
    $id = uniqid();
    @endphp
    <li>
      @if (isset($row['sub']))
      <button type="button"
        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group"
        aria-controls="dropdown-{{$id}}" data-collapse-toggle="dropdown-{{$id}}">
        {!!$row['icon']!!}
        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{$row['name']}}</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="m1 1 4 4 4-4" />
        </svg>
      </button>
      <ul id="dropdown-{{$id}}" class="py-2 space-y-2">
        @foreach ($row['sub'] as $rowSub)
        <li>
          <a href="#"
            @click="$wire.dispatch('change-page', {page: '{{$rowSub['name']}}'})"
            class="
            flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:font-bold hover:text-primary hover:!border-r-4 hover:!border-r-primary hover:!rounded-r-none
            @if($page == $rowSub['name'])
            !font-bold !text-primary !border-r-4 !border-r-primary !rounded-r-none
            @endif
            ">{{$rowSub['name']}}</a>
        </li>
        @endforeach
      </ul>
      @endif

    </li>
    @endforeach
    <li>
      <a href="#" @click="$wire.dispatch('change-page', {page: 'help'})" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-primary">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
        </svg>
        <span class="ms-3">Pusat Bantuan</span>
      </a>
    </li>
    <li>
      <a
      @if(!auth()->user()->store)
      href="#"
      @click="$wire.dispatch('change-page', {page: 'Kios Saya'})"
      @else
      href="#"
      @click="$wire.dispatch('change-page', {page: 'Kios Saya'})"
      {{-- href="{{url('store')}}" --}}
      @endif
      class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-primary">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
        </svg>
        <span class="ms-3">Kios Saya</span>
      </a>
    </li>
  </ul>
</div>
