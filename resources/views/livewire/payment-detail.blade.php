<div class="container py-4 max-sm:pb-[4rem]" @if($payment->status == 'pending' || ($payment->status == 'settlement' && $payment->transaction_type == 'instant' && in_array($payment->singleTransaction->status,['unprocessed','confirmed']))) wire:poll.5s @endif>
  <!-- Title -->
  <h1 class="text-lg sm:text-2xl font-semibold text-gray-800 mb-4">Detail Transaksi</h1>

  <!-- Order Information -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <h2 class="text-lg font-bold {{$payment->getStatusColor()}}">{{$payment->getStatusText()}}</h2>
    <hr class="my-4" />
    <div class="mt-2 text-sm text-gray-600 space-y-2">
      <p class="flex items-center justify-between"><span>No. Pembayaran:</span><span class="font-bold text-primary">PAY/{{$payment->id}}</span></p>
      <p class="flex items-center justify-between"><span>Tanggal Pembelian:</span> <span class="font-medium text-gray-800">{{\Carbon\Carbon::parse($payment->created_at)->translatedFormat('l, d F Y H:i')}}</span></p>
    </div>
  </div>

  <!-- Product Details -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-3">Detail Produk</h3>
    <hr class="my-4" />
    <div class="divide-y space-y-4">
      @foreach ($list as $row)
      <div class="space-y-4">
        @if ($payment->transaction_type == 'instant')
          {{-- ALERT --}}
          @if ($row->status == 'finished')
          <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p class="font-bold">Sukses!</p>
            @if (in_array($payment->singleTransaction->product->category, ['Pulsa','Data']))
            <div class="">Transaksi berhasil, silahkan cek pulsa anda. 🥳</div>
            @elseif(in_array($payment->singleTransaction->product->category, ['Games']))
            <div class="">Transaksi berhasil, silahkan cek akun game anda. 🥳</div>
            @else
            <div class="">Transaksi berhasil, silahkan cek saldo anda. 🥳</div>
            @endif
          </div>
          @elseif ($row->status == 'rejected')
          <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 space-y-3" role="alert">
            <p class="font-bold">Gagal!</p>
            <div class="">Transaksi gagal, silahkan hubungi admin melalui whatsapp. 🥲</div>
            <div class="flex flex-wrap space-x-2">
              @foreach (config('cs') as $key => $rowCs)
              <a target="_blank" href="https://api.whatsapp.com/send?phone={{$rowCs}}&text=Halo min saya sudah bayar tetapi transaksi gagal%0A%0ATransaction ID: PAY/{{$payment->id}}%0ATransaction ID: INV/{{$payment->singleTransaction->id}}" class="flex items-center space-x-2 bg-green-800 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-300 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                  <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                </svg>
                <div>{{$rowCs}}</div>
              </a>
              @endforeach
            </div>
          </div>
          @endif
        @endif
        <div class="w-full flex justify-between items-center space-x-2 w-full">
          <div class="flex items-center justify-between w-full">
            <span class="text-black">
              @if ($payment->transaction_type == 'basic')
              {{$row->store->name}}
              @else
              Top Up Instant
              @endif
            </span>
            <span class="{{$row->getStatusColor()}}">INV/{{$row->id}} - {{$row->getStatusText()}}</span>
          </div>
        </div>
        @if ($payment->transaction_type == 'basic')
          @foreach ($row->details as $rowDetail)
          <div class="">
            <div class="flex items-start space-x-4 mb-4">
              <img src="{{productImage($rowDetail->product->mainImage())}}" alt="Product" class="w-20 h-20 object-cover rounded-md">
              <div>
                <p class="text-sm text-gray-900 font-bold">{{$rowDetail->product->title}}</p>
                <p class="text-sm text-gray-900 mt-1">{{$rowDetail->quantity}} x Rp{{number_format($rowDetail->price,0,',','.')}}</p>
              </div>
            </div>
            <div class="relative z-0 w-full">
              {{$detail->note ?? 'Tidak ada catatan'}}
            </div>
          </div>
          @endforeach
        @else
          <div class="">
            <div class="flex max-sm:flex-col sm:items-start max-sm:space-y-4 sm:space-x-4 mb-4">
              <div class="flex items-center sm:items-start space-x-4 flex-grow">
                <img src="{{productInstantImage($row->product)}}" alt="Product" class="w-20 h-20 object-cover rounded-md">
                <div class="flex-grow">
                  <p class="text-sm text-gray-900 font-bold">{{$row->product->title}}</p>
                  <p class="text-sm text-gray-900 mt-1">{{$row->quantity}} x <span class="font-bold text-black">Rp{{number_format($payment->fee_wrap_up,0,',','.')}}</span></p>
                </div>
              </div>
              @php
              $informations = $payment->singleTransaction->data;
              @endphp
              @if (is_array($informations))
              <div>
                @foreach ($informations as $rowInfo)
                  @if ($rowInfo['value'])
                  <div class="flex items-center justify-start sm:justify-end space-x-2">
                    <div>{{$rowInfo['label']}}</div>
                    <div class="font-bold text-black">{{$rowInfo['value']}}</div>
                  </div>
                  @endif
                @endforeach
              </div>
              @endif
            </div>
            @if (isset($detail->note) && $detail->note)
            <div class="relative z-0 w-full">
              {{$detail->note ?? 'Tidak ada catatan'}}
            </div>
            @endif
          </div>
        @endif
      </div>
      @endforeach
    </div>
    {{-- <div class="mt-4">
      <button class="px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-600">Beli Lagi</button>
    </div> --}}
  </div>

  <!-- Payment Details -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-3">Rincian Pembayaran</h3>
    <hr class="my-4" />
    <div class="text-sm text-gray-600 space-y-2">
      <p class="flex items-center justify-between"><span class="font-medium text-gray-800">Metode Pembayaran</span> <span>{{$payment->data['payment_name']}}</span></p>
      <p class="flex items-center justify-between"><span class="font-medium text-gray-800">Subtotal Harga Barang</span> <span>Rp{{number_format($payment->fee_wrap_up,0,',','.')}}</span></p>
      <p class="flex items-center justify-between pb-2"><span class="font-medium text-gray-800">Biaya Channel Pembayaran</span> <span>Rp{{number_format($payment->fee_amount,0,',','.')}}</span></p>
      <hr class="my-4" />
      <p class="flex items-center justify-between font-bold text-lg"><span class="font-medium text-gray-800">Total Pembayaran</span> <span>Rp{{number_format($payment->amount,0,',','.')}}</span></p>
    </div>
  </div>

  <div class="flex justify-between space-x-2 mb-10">
    @if ($payment->status == 'pending')
    <a href="{{url('payment/'.$payment->id)}}" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow w-full text-center" wire:navigate>Bayar Sekarang</a>
    @endif
    <a href="{{url('/')}}" class="bg-gray-100 text-black px-4 py-2 rounded-lg shadow hover:bg-gray-300 w-full text-center" wire:navigate>Belanja Lagi</a>
  </div>
</div>
