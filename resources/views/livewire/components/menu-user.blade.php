<div class="space-y-4 p-4">
  <div class="flex items-center gap-3">
    <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-lg border-2 border-slate-600">
      <img class="w-full object-cover" src="{{profile(auth()->user())}}" alt="Profile" />
    </div>
    <div>
      <div class="flex gap-1 text-sm font-semibold">
        <span>{{auth()->user()->name}}</span>
        <span class="text-sky-400">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"></path>
          </svg>
        </span>
      </div>
      <div class="text-xs text-slate-400">{{auth()->user()->email}}</div>
    </div>
    <button class="ml-auto" data-bs-dismiss="offcanvas">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
      </svg>
    </button>
  </div>
  <hr />
  <div class="mb-4">
    <div class="font-semibold mb-4">Riwayat Transaksi</div>
    <div class="grid grid-cols-3 gap-2">
      <div class="flex flex-col items-center justify-center space-y-2 border hover:shadow p-4 rounded cursor-pointer" @click="Livewire.navigate('{{url('user/transaction')}}?tab={{'unprocessed'}}')">
        <img src="{{url('assets/images/time-is-money.png')}}" alt="" srcset="" class="w-[24px] h-[24px]" />
        <div class="text-xs text-center">Menunggu Pembayaran</div>
      </div>
      <div class="flex flex-col items-center justify-center space-y-2 border hover:shadow p-4 rounded cursor-pointer" @click="Livewire.navigate('{{url('user/transaction')}}?tab={{'confirmed'}}')">
        <img src="{{url('assets/images/payment-services.png')}}" alt="" srcset="" class="w-[24px] h-[24px]" />
        <div class="text-xs text-center">Menunggu Proses</div>
      </div>
      <div class="flex flex-col items-center justify-center space-y-2 border hover:shadow p-4 rounded cursor-pointer" @click="Livewire.navigate('{{url('user/transaction')}}?tab={{'processed'}}')">
        <img src="{{url('assets/images/completed-task.png')}}" alt="" srcset="" class="w-[24px] h-[24px]" />
        <div class="text-xs text-center">Sedang di Proses</div>
      </div>
    </div>
  </div>
  <hr />
  <div class="grid gap-2" x-data="{ openAbout: false }">
    @auth
    @if (auth()->user()->role == 'admin')
    <a href="{{url('admin')}}" class="flex items-center gap-3 rounded-md py-2 px-3 col-span-12 border hover:shadow">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-speedometer2" viewBox="0 0 16 16">
        <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.39.39 0 0 0-.029-.518z"/>
        <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A8 8 0 0 1 0 10m8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3"/>
      </svg>
      <span>Dashboard Admin</span>
    </a>
    @endif
    @endauth
    <a href="{{url('user/profile')}}" wire:navigate class="flex items-center gap-3 rounded-md py-2 px-3 col-span-6 border hover:shadow">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
      </svg>
      <span>Akun Saya</span>
    </a>
    <a href="{{url('store')}}" class="flex items-center gap-3 rounded-md py-2 px-3 border hover:shadow relative col-span-6">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
      </svg>
      <span>Toko Saya</span>
    </a>
    <button @click="openAbout=true" class="flex items-center gap-3 rounded-md py-2 px-3 border hover:shadow col-span-12">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
      </svg>
      <span>Tentang</span>
    </button>
    @include('components.modals.about')
  </div>
  {{-- <a
  href="admin"
  class="flex justify-center gap-3 ring-2 ring-gray-200 rounded-md py-2 px-3 font-semibold focus:ring-2 focus:ring-violet-400 w-full">
    <div>
      <span class="inline-block">Dashboard</span>
    </div>
  </a> --}}
  <button type="button" wire:click="logout" class="flex justify-center gap-3 rounded-md bg-primary py-2 px-3 font-semibold hover:bg-violet-500 focus:ring-2 focus:ring-violet-400 text-white w-full">
    <div wire:loading.remove>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6 inline-block">
        <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 006 5.25v13.5a1.5 1.5 0 001.5 1.5h6a1.5 1.5 0 001.5-1.5V15a.75.75 0 011.5 0v3.75a3 3 0 01-3 3h-6a3 3 0 01-3-3V5.25a3 3 0 013-3h6a3 3 0 013 3V9A.75.75 0 0115 9V5.25a1.5 1.5 0 00-1.5-1.5h-6zm10.72 4.72a.75.75 0 011.06 0l3 3a.75.75 0 010 1.06l-3 3a.75.75 0 11-1.06-1.06l1.72-1.72H9a.75.75 0 010-1.5h10.94l-1.72-1.72a.75.75 0 010-1.06z" clip-rule="evenodd"></path>
      </svg>
      <span class="inline-block">Keluar</span>
    </div>
    <span wire:loading>
      @include('components.spinner')
    </span>
  </button>
</div>
