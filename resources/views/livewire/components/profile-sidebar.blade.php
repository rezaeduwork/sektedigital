<div class="w-full space-y-2 sm:space-y-4" x-data="{profileShow: false}">
  <button class="w-full flex items-center justify-between bg-gray-100 p-2 rounded-lg border sm:!hidden" @click="profileShow = !profileShow">
    <div class="flex items-center space-x-2">
      <img src="{{profile(auth()->user())}}" alt="" class="size-[24px] rounded-full border object-contain object-center">
      <div>Profile</div>
      <div>-</div>
      <div class="flex items-center space-x-1">
        <img src="{{url('assets/images/wallet.png')}}" alt="" srcset="" class="size-3 sm:size-6 shrink-0">
        <div>Rp{{number_format(auth()->user()->balance,0,',','.')}}</div>
      </div>
    </div>
    <div class="flex items-center justify-center transition-all duration-300" :class="profileShow ? 'rotate-180':''">
      <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-chevron-down size-[16px]" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
      </svg>
    </div>
  </button>
  <div class="transition-all duration-100 sm:space-y-4" :class="profileShow ? 'max-h-[650px] space-y-4':'max-sm:max-h-[0px] max-sm:overflow-hidden max-sm:!my-0'">
    <div class="flex flex-col items-center sm:space-y-4" x-data :class="profileShow ? 'space-y-4':''">
      <img src="{{$photo ? $photo->temporaryUrl(): profile(auth()->user())}}" alt="" class="w-[56px] h-[56px] rounded-full border object-contain object-center">
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
    <div class="justify-between p-2 space-x-2 shadow border rounded flex items-center bg-violet-600 text-white hidden">
      <div class="shrink-0 flex-grow">Rank</div>
      <div class="flex items-center justify-between">
        <div class="flex items-center shrink-0">
          <img src="{{url('assets/images/rank/common.png')}}" alt="" srcset="" class="size-4 shrink-0 -mt-1">
          <div class="ms-1 font-semibold">Common</div>
        </div>
        {{-- <button type="button" class="">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
          </svg>
        </button> --}}
      </div>
    </div>
    <ul>
      <li>
        <a href="{{url('user/wallet')}}" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group text-xs" wire:navigate>
          <img src="{{url('assets/images/wallet.png')}}" alt="" srcset="" class="size-6 shrink-0">
          <div class="ms-3 flex w-full justify-between items-center">
            <div>Saldo</div>
            <div class="flex items-center space-x-2">
              <div>{{number_format(auth()->user()->balance)}}</div>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3 text-primary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
              </svg>
            </div>
          </div>
        </a>
      </li>
      {{-- <li>
        <a href="{{url('user/wallet')}}" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group text-xs" wire:navigate>
          <img src="{{url('assets/images/coin.png')}}" alt="" srcset="" class="size-6 shrink-0">
          <div class="ms-3 flex w-full justify-between items-center">
            <div>Koin</div>
            <div class="flex items-center space-x-2">
              <div>0</div>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3 text-primary">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
              </svg>
            </div>
          </div>
        </a>
      </li> --}}
    </ul>
  </div>
  <ul></ul>
  <hr class="max-sm:hidden" />
  <ul class="max-sm:flex max-sm:items-start max-sm:max-w-full max-sm:overflow-x-auto max-sm:space-x-2">
    @php
    $menus = [
      [
        'icon' => '
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 max-sm:hidden sm:size-5 text-gray-500 transition duration-75 group-hover:text-primary">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
          </svg>
        ',
        'name' => 'Akun Saya',
        'sub' => [
          [
            'name' => 'Profil',
            'url' => url('user/profile')
          ]
        ]
      ],
      [
        'icon' => '
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 max-sm:hidden sm:size-5 text-gray-500 transition duration-75 group-hover:text-primary">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
          </svg>
        ',
        'name' => 'Pesanan Saya',
        'url' => url('user/transaction')
      ]
    ];
    @endphp
    @foreach ($menus as $row)
    @php
    $id = uniqid();
    @endphp
    <li wire:key="menu-item-{{str_replace(' ','',$row['name'])}}" class="shrink-0 max-sm:relative max-sm:bg-white max-sm:rounded-lg">
      @if (isset($row['sub']))
      <button type="button"
        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group max-sm:text-xs"
        aria-controls="dropdown-{{$id}}" data-collapse-toggle="dropdown-{{$id}}">
        {!!$row['icon']!!}
        <div class="max-sm:space-x-2 flex items-center w-full justify-between">
          <span class="flex-1 sm:ms-3 text-left whitespace-nowrap">{{$row['name']}}</span>
          <svg class="size-2 sm:size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 4 4 4-4" />
          </svg>
        </div>
      </button>
      <ul id="dropdown-{{$id}}" class="pt-2 sm:py-2 sm:space-y-2 hidden">
        @foreach ($row['sub'] as $rowSub)
        <li class="max-sm:border-t">
          <a href="{{$rowSub["url"]}}"
            wire:navigate
            class="
            block items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg sm:pl-11 group hover:font-bold hover:text-primary max-sm:text-xs max-sm:rounded-lg
            @if(request()->url() == $rowSub['url'])
            !font-bold !text-primary
            @endif
            ">{{$rowSub['name']}}</a>
        </li>
        @endforeach
      </ul>
      @else
      <a href="{{$row["url"]}}" class="
      flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:font-bold hover:text-primary max-sm:text-xs
      @if(request()->url() == $row['url'])
      !font-bold !text-primary
      @endif
      " wire:navigate>
        {!!$row["icon"]!!}
        <span class="sm:ms-3">{{$row["name"]}}</span>
      </a>
      @endif

    </li>
    @endforeach
    <li class="shrink-0 max-sm:bg-white max-sm:rounded-lg">
      <a
      href="{{url('user/store')}}"
      wire:navigate
      class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group max-sm:text-xs">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 max-md:hidden sm:size-5 text-gray-500 transition duration-75 group-hover:text-primary">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
        </svg>
        <span class="sm:ms-3">Toko Saya</span>
      </a>
    </li>
    {{-- <li class="shrink-0 max-sm:bg-white max-sm:rounded-lg">
      <a href="#" @click="$wire.dispatch('change-page', {page: 'help'})" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group max-sm:text-xs">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 max-md:hidden sm:size-5 text-gray-500 transition duration-75 group-hover:text-primary">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
        </svg>
        <span class="sm:ms-3">Pusat Bantuan</span>
      </a>
    </li> --}}
  </ul>
</div>
