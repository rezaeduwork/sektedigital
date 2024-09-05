<div class="mb-5 container">
  <div class="flex items-center justify-between">
    <div class="text-3xl font-semibold w-full text-gray-700 mb-6">Detail Saldo</div>
    <button type="button" class="text-primary px-4 py-2 underline rounded-md shrink-0">Download Riwayat</button>
  </div>
  <div class="flex items-start flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
    <div class="shrink-0 w-full md:w-[250px] py-2 px-4 bg-white shadow rounded items-center">
      <div class="flex space-x-4 items-center">
        <img src="{{ url('assets/images/wallet.png') }}" alt="" srcset="" class="size-6 shrink-0">
        <div class="w-full">
          <div>Saldo Saya</div>
          <div class="text-lg font-black">Rp. {{number_format(auth()->user()->balance)}}</div>
        </div>
        <button class="shrink-0" type="button">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
          </svg>
        </button>
      </div>
      <div class="p-2">
        Lihat penjelasan Saldo <a href="http://" class="text-link">di sini</a>
      </div>
      <hr class="mt-2 mb-3" />
      <div class="flex flex-col space-y-2 md:flex-row md:space-x-2 md:space-y-0 text-white">
        <button class="bg-primary py-2 w-full rounded">Deposit</button>
        <button class="bg-primary py-2 w-full rounded">Tarik Saldo</button>
      </div>
      <a href="#" target="_blank" class="text-green-600 mt-2 block underline">Deposit Manual ? Klik disini</a>
      <a href="#" target="_blank" class="text-red-600 mt-2 block underline">Deposit / Withdrawal bermasalah ? Lapor disini</a>
      <hr class="mt-3 mb-2" />
      <div class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group text-xs">
        <img src="{{ url('assets/images/coin.png') }}" alt="" srcset="" class="size-6 shrink-0">
        <div class="ms-3 flex w-full justify-between items-center">
          <div>Koin</div>
          <div>0</div>
        </div>
      </div>
      <div class="p-2">
        Lihat penjelasan Koin <a href="http://" class="text-link">di sini</a>
      </div>
    </div>
    <div class="w-full bg-white shadow rounded p-4">
      <livewire:user.wallet.history>
    </div>
  </div>
</div>
