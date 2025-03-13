<div class="rounded space-y-4">
  <div>
    <h2 class="font-semibold mb-2">Masukan Alamat Wallet Tujuan</h2>
    <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-2" role="alert">
      <p class="font-bold">Perhatian!</p>
      <div class="">Mohon perhatikan alamat tujuan, platform tidak bertanggung jawab atas kesalahan pengguna</div>
    </div>
    <input type="text" placeholder="alamat {{$product->code}}" wire:model.live.debounce.500ms="address" class="form-control w-full p-2 border border-gray-200 outline-none ring-none rounded">
  </div>
  <div>
    <h2 class="font-semibold mb-2">Masukan Nominal</h2>
    <input type="number" placeholder="Rp. " wire:model.live.debounce.500ms="amount" class="form-control w-full p-2 border border-gray-200 outline-none ring-none rounded">
  </div>
  <div class="flex gap-2 text-xs">
    <span class="p-2 bg-green-100 rounded-lg flex items-center gap-2">
        🏆 Terbaik
    </span>
    <span class="p-2 bg-white rounded-lg flex items-center gap-2">
        🚀 Transaksi Instan
    </span>
    <span class="p-2 bg-white rounded-lg flex items-center gap-2">
        💰 Termurah
    </span>
    <span class="p-2 bg-white rounded-lg flex items-center gap-2">
        ⏳ 5-10 Detik Selesai
    </span>
  </div>
</div>
