<div class="rounded space-y-4">
  <div class="space-y-4">
    <div>
      <h2 class="font-semibold mb-2">Provider</h2>
      <input type="text" value="{{$provider}}" disabled class="w-full p-0 border border-none bg-transparent outline-none ring-none rounded">
    </div>
    <div>
      <h2 class="font-semibold mb-2">Nomor HP</h2>
      <input type="number" value="{{$phone}}" disabled class="w-full p-0 border border-none bg-transparent outline-none ring-none rounded">
    </div>
    <div>
      <h2 class="font-semibold mb-2">Harga</h2>
      <input type="text" value="Rp{{number_format($amount,0,',','.')}}" disabled class="w-full p-0 border border-none bg-transparent outline-none ring-none rounded">
    </div>
  </div>
  {{-- <div class="flex gap-2 text-xs">
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
  </div> --}}
</div>
