<template x-teleport="body">
  <div class="relative z-[9999]" style="display: none;" x-show="openAbout" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!--
      Background backdrop, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100"
        To: "opacity-0"
    -->
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
      <div class="flex min-h-full items-center justify-center sm:p-4 text-center sm:items-center sm:p-0">
        <!--
          Modal panel, show/hide based on modal state.

          Entering: "ease-out duration-300"
            From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            To: "opacity-100 translate-y-0 sm:scale-100"
          Leaving: "ease-in duration-200"
            From: "opacity-100 translate-y-0 sm:scale-100"
            To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        -->
        <div class="relative transform overflow-hidden sm:rounded-lg max-sm:min-h-screen max-sm:flex max-sm:flex-col max-sm:justify-between bg-white text-left shadow-xl transition-all sm:my-8 w-full sm:max-w-xl">
          <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 space-y-2 max-sm:h-full">
            <header>
              <h1 class="text-lg sm:text-xl font-semibold flex items-center justify-between">
                <div>Tentang Bitneet</div>
                <button @click="openAbout=false">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                  </svg>
                </button>
              </h1>
              <p>Marketplace Digital Tanpa Batas & Tanpa Pengiriman</p>
            </header>
            <hr />
            <main class="max-sm:overflow-y-auto max-sm:max-h-[calc(100vh-72px-107px)]">
              <h2 class="font-semibold">Siapa Kami</h2>
              <p class="mb-2"><strong class="font-bold">Bitneet</strong> adalah marketplace digital yang berfokus pada jual beli produk digital tanpa proses pengiriman fisik. <br />Mulai dari software, aset desain, eBook, hingga layanan online — semuanya bisa langsung dikirim dan diterima secara instan.</p>

              <h2 class="font-semibold">Misi Kami</h2>
              <p class="mb-2">Kami percaya bahwa masa depan perdagangan adalah cepat, global, dan tanpa batas. Misi kami adalah memberdayakan kreator dan pengguna untuk bertukar nilai digital secara aman dan instan dari mana saja di dunia.</p>

              <h2 class="font-semibold">Mengapa Produk Digital?</h2>
              <p class="mb-2">Produk digital ramah lingkungan, cepat diakses, dan praktis. Tanpa kemasan atau ongkos kirim, Anda bisa mendapatkan apa yang Anda butuhkan hanya dalam hitungan detik.</p>

              <h2 class="font-semibold">Gabung Bersama Kami</h2>
              <p class="mb-2">
                <a class="text-link underline"
                @if(auth()->check())
                href="{{url('profile/shop')}}" wire:navigate
                @else
                data-bs-toggle="modal" href="#userModal"
                @endif>Mulai jual atau beli</a>
                produk digital hari ini di Bitneet. Daftar sekarang dan rasakan pengalaman berdagang digital tanpa batas.
              </p>
            </main>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <button type="button" @click="openAbout = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Kembali</button>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>
