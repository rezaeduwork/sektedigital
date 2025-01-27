<div class="w-full flex items-center justify-center">
  <div class="bg-white rounded-2xl shadow-lg p-6 max-w-md w-full">
    <h1 class="text-xl font-bold text-center text-gray-800 mb-2">Selesaikan pembayaran dalam</h1>
    <div class="text-center text-orange-500 text-2xl font-bold mb-4"
     x-data="countdown('{{$paymentDetail['expired_time']}}')"
     x-init="startCountdown()"
     x-text="timeLeft"></div>
    <p class="text-center text-gray-600 mb-2">Batas Akhir Pembayaran</p>
    <p class="text-center font-bold text-gray-800">{{\Carbon\Carbon::createFromTimestamp($paymentDetail['expired_time'])->translatedFormat('l, d F Y H:i')}}</p>

    <div class="border-t border-gray-200 my-6"></div>

    <div class="my-6">
        <div class="flex items-center justify-between">
            <p class="text-gray-500">Total Tagihan</p>
            <p class="text-gray-800 font-bold text-lg">Rp{{number_format($paymentDetail['amount'],0,',','.')}}</p>
        </div>
    </div>

    <div class="border-t border-gray-200 my-6"></div>

    @if (in_array($paymentDetail['payment_method'], ['QRIS2','QRIS']))
    <img src="{{$paymentDetail['qr_url']}}" alt="" srcset="" class="w-full h-auto mb-6">
    @endif

    <div class="mb-6 space-y-4 border rounded p-4">
      <div>
        <div class="font-semibold text-lg">Cara Pembayaran</div>
      </div>
      @foreach ($paymentDetail['instructions'] as $row)
      <div class="border-b pb-4">
        <div class="font-semibold mb-2">{{$row['title']}}</div>
        <div class="space-y-1">
          @foreach ($row['steps'] as $index => $itemStep)
          <div class="flex items-start space-x-1">
            <div class="w-[18px] shrink-0">{{$index + 1}}.</div>
            <div>{!!$itemStep!!}</div>
          </div>
          @endforeach
        </div>
      </div>
      @endforeach
    </div>

    <div class="flex justify-between space-x-2">
        <a href="{{url('user/transaction')}}" class="bg-primary text-white px-4 py-2 rounded-lg shadow hover:bg-primary w-full text-center" wire:navigate>Cek Status Pembayaran</a>
        <a href="{{url('/')}}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg shadow hover:bg-gray-300 w-full text-center" wire:navigate>Belanja Lagi</a>
    </div>
  </div>
  @include('components.scripts.countdown')
</div>
