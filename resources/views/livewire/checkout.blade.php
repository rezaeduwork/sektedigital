<div class="mb-6 bg-white shadow">
  <div class="px-4 py-6">
    <p class="text-xl font-medium">Informasi Pembeli</p>
    <div class="flex items-center space-x-4 mt-4">
      <div>
        nama pembeli: <span class="font-semibold text-lg text-gray-900">{{auth()->user()->name}}</span>
      </div>
      <div>
        email: <span class="font-semibold text-lg text-gray-900">{{auth()->user()->email}}</span>
      </div>
      <div>
        nomor hp: <span class="font-semibold text-lg text-gray-900">{{auth()->user()->phone ?? '-'}}</span>
      </div>
      <div>
        tanggal: {{now()}}
      </div>
    </div>
  </div>
  <hr />
  <div class="grid lg:grid-cols-12 mt-6">
    <div class="px-4 col-span-8">
      <p class="text-xl font-medium">Ringkasan Transaksi</p>
      <div class="mt-6 space-y-3 rounded-lg bg-white">
        @foreach ($availableCarts as $row)
        <div class="flex flex-col rounded-lg bg-white sm:flex-row sm:items-center">
          <img class="m-2 size-12 rounded-md object-cover object-center"
            src="{{productImage($row->product->images()->where('type', 'main')->first())}}"
            alt="" />
          <div class="flex w-full flex-col px-4">
            <span class="font-semibold">{{$row->product->title}}</span>
            <span class="float-right text-gray-400 text-xs">{{$row->product->category->name}}</span>
          </div>
          <div class="ml-auto shrink-0 space-y-1">
            <div class="font-bold">Rp. {{number_format($row->product->price)}} x {{$row->quantity}}</div>
            <a href="" class="flex items-center space-x-2 text-gray-500 text-xs shrink-0 ml-auto w-full justify-end">
              <img src="{{ storeProfile($row->product->store) }}" alt="Ecommerce" class="size-3">
              <div class="">{{ $row->product->store->name }}</div>
            </a>
          </div>
        </div>
        <hr />
        @endforeach
      </div>
    </div>
    <div class="px-4 col-span-4">
      <p class="text-xl font-medium">Detail Pembayaran</p>
      <p class="text-gray-400">Pilih metode pembayaran dibawah ini.</p>
      <div class="">
        <div class="mt-6 flex items-center justify-between">
          <p class="text-sm font-medium text-gray-900">Total</p>
          <p class="text-2xl font-semibold text-gray-900">Rp. {{number_format(totalTransaction())}}</p>
        </div>
      </div>
      <button
      type="button"
      wire:click="pay"
      class="mt-4 mb-8 w-full rounded-md bg-green-600 px-6 py-3 font-medium text-white">
        <div wire:loading.remove wire:key="pay" wire:target="pay">
          <div>Bayar</div>
        </div>
        <div wire:loading wire:key="pay" wire:target="pay">@include('components.spinner')</div>
      </button>
    </div>
  </div>

</div>
