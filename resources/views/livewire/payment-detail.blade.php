<div class="">
  <!-- Title -->
  <h1 class="text-2xl font-semibold text-gray-800 mb-4">Detail Transaksi</h1>

  <!-- Order Information -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <h2 class="text-lg font-medium text-gray-700">Pesanan Selesai</h2>
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
    <div class="divide-y">
      @foreach ($payment->transactions as $row)
      <div class="mb-4">
        <div class="w-full mb-4 flex justify-between items-center space-x-2 w-full">
          <div class="flex items-center justify-between w-full">
            <span class="text-black font-semibold">
              {{$row->store->name}}
            </span>
            <span class="text-primary font-semibold">INV/{{$row->id}}</span>
          </div>
        </div>
        @foreach ($row->details as $rowDetail)
        <div class="mb-4">
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
      <p class="flex items-center justify-between"><span class="font-medium text-gray-800">Metode Pembayaran:</span> <span>{{$payment->data['payment_name']}}</span></p>
      <p class="flex items-center justify-between pb-2"><span class="font-medium text-gray-800">Subtotal Harga Barang:</span> <span>Rp{{number_format($payment->amount,0,',','.')}}</span></p>
      <hr class="my-4" />
      <p class="flex items-center justify-between font-bold text-lg"><span class="font-medium text-gray-800">Total Belanja:</span> <span>Rp{{number_format($payment->amount,0,',','.')}}</span></p>
    </div>
  </div>
</div>
