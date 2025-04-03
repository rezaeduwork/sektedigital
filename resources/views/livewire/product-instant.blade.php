<div class="container bg-white rounded-lg py-4">
  <div class="mx-auto mb-4">
    <div class="flex gap-6">
      <!-- Produk -->
      <div class="w-full">
        <nav class="text-xs text-gray-500 mb-6">
          <a href="#" class="text-blue-500">Beranda</a> > <span>{{$product->title}}</span>
        </nav>
        <div class="flex items-start gap-4 mb-6">
          @if ($product->image)
          <img src="{{url($product->image)}}" alt="ML Logo" class="w-12 h-12 rounded-full shrink-0">
          @else
          <div class="w-12 h-12 rounded-full shrink-0 bg-gray-100 border border-gray-400"></div>
          @endif
          <div class="space-y-2">
            <h1 class="font-bold flex items-center">
              <span class="text-xl">
                {{$product->title}}
              </span>
            </h1>
            <p class="text-gray-500 flex gap-2 text-sm">
              {{$product->highlight}}
            </p>
            <div class="flex pt-2">
              @if ($product->category == 'buy-crypto')
              <livewire:product-instant.crypto-live-price :ticker="$product->code">
              @endif
              <div class="px-2 py-1 bg-white rounded flex items-center gap-2 text-red-600 font-semibold text-xs">
                Stok ({{$product->stock == -1 ? 'Unlimited': $product->stock}})
              </div>
            </div>
          </div>
        </div>
        @if ($product->category == 'buy-crypto')
        <livewire:product-instant.crypto-input :product="$product">
        @elseif(in_array($product->category, ['Pulsa','Data']))
        <livewire:product-instant.pulsadata-input :product="$product">
        @endif
        <hr class="my-4" />
        <div class="mb-4">
          <h2 class="font-semibold mb-2">✨ Ulasan</h2>
          <div class="w-full">
            <div>
              @php
              $productRatingsList = [];
              @endphp
              @forelse ($productRatingsList as $row)
              <div class="flex border-b gap-5 mb-6 pb-6">
                <!-- img -->
                <!-- img -->
                <img src="{{profile($row->user)}}" alt=""
                  class="rounded-full h-12 w-12" />
                <div class="flex flex-col gap-4">
                  <div class="flex flex-col gap-1">
                    <h4 class="text-base">{{$row->user->name}}</h4>
                    <!-- select option -->
                    <!-- content -->
                    <p class="text-xs md:flex flex-row gap-3">
                      <span class="text-gray-500">{{\Carbon\Carbon::parse($row->created_at)->diffForHumans()}}</span>
                      {{-- <span class="text-primary font-semibold">Verified Purchase</span> --}}
                    </p>
                  </div>
                  <!-- rating -->
                  <div class="md:flex md:items-center gap-3">
                    <small class="text-yellow-500 inline-flex items-center">
                      @foreach (range(1,$row->rating) as $item)
                      <svg xmlns="../www.w3.org/2000/svg.html"
                        class="icon icon-tabler icon-tabler-star-filled text-yellow-300" width="14" height="14"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path
                          d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                          stroke-width="0" fill="currentColor"></path>
                      </svg>
                      @endforeach
                    </small>
                    {{-- <span class="text-gray-900 font-semibold">Need to recheck the weight at delivery
                      point</span> --}}
                  </div>
                  <!-- text-->
                  <p>{{$row->feedback}}</p>
                  <div class="flex gap-2">
                    @foreach ($product->images()->take(5)->get() as $rowImage)
                    <img src="{{productImage($rowImage)}}" alt="" class="w-16 h-auto border-4 rounded" />
                    @endforeach
                  </div>
                  <!-- icon -->
                  {{-- <div class="flex justify-end gap-4">
                    <a href="#" class="text-gray-500">
                      <svg xmlns="../www.w3.org/2000/svg.html"
                        class="icon icon-tabler icon-tabler-thumb-up inline-block" width="18"
                        height="18 " viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                          d="M7 11v8a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-7a1 1 0 0 1 1 -1h3a4 4 0 0 0 4 -4v-1a2 2 0 0 1 4 0v5h3a2 2 0 0 1 2 2l-1 5a2 3 0 0 1 -2 2h-7a3 3 0 0 1 -3 -3" />
                      </svg>
                      Helpful
                    </a>
                    <a href="#" class="text-gray-500">
                      <svg xmlns="../www.w3.org/2000/svg.html"
                        class="inline-block icon icon-tabler icon-tabler-flag" width="18" height="18"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 5a5 5 0 0 1 7 0a5 5 0 0 0 7 0v9a5 5 0 0 1 -7 0a5 5 0 0 0 -7 0v-9z" />
                        <path d="M5 21v-7" />
                      </svg>
                      Report abuse
                    </a>
                  </div> --}}
                </div>
              </div>
              @empty
              Belum ada review
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <!-- Informasi Pesanan -->
      <livewire:product-instant.payment-info :product="$product">
    </div>
  </div>
  <div class="mb-4">
    <div class="w-full mx-auto">
      <h2 class="font-semibold mb-4">❓Pertanyaan yang sering diajukan</h2>
      <div class="space-y-2" x-data="{ open: 0 }">
        @foreach ([
        [
          'question' => 'Apa itu produk instan?',
          'answer' => 'Produk instan adalah produk yang dapat dibeli dan langsung diproses secara otomatis oleh sistem tanpa menunggu konfirmasi atau tindakan dari seller.'
        ],
        [
          'question' => 'Apa perbedaan produk instan dengan produk lainnya?',
          'answer' => 'Produk instan diproses secara otomatis oleh sistem setelah pembayaran berhasil, sedangkan produk lainnya diproses secara manual oleh seller, yang mungkin memerlukan waktu lebih lama.'
        ],
        [
          'question' => 'Bagaimana cara membeli produk instan?',
          'answer' => 'Cukup pilih produk instan yang diinginkan, lakukan pembayaran, dan sistem akan langsung memproses pesanan Anda tanpa perlu menunggu seller.'
        ],
        [
          'question' => 'Berapa lama proses transaksi produk instan?',
          'answer' => 'Produk instan biasanya diproses dalam hitungan detik hingga beberapa menit setelah pembayaran berhasil dikonfirmasi.'
        ],
        [
          'question' => 'Apa yang harus dilakukan jika transaksi instan gagal?',
          'answer' => 'Jika transaksi instan gagal, periksa status pembayaran Anda atau hubungi layanan pelanggan untuk bantuan lebih lanjut.'
        ],
        [
          'question' => 'Apakah ada biaya tambahan untuk produk instan?',
          'answer' => 'Biasanya tidak ada biaya tambahan, tetapi beberapa metode pembayaran mungkin memiliki biaya administrasi yang ditentukan oleh penyedia layanan pembayaran.'
        ]
      ]
       as $key => $row)
        <div class="border rounded-lg bg-white">
          <button @click="open = open === {{$key}} ? 0 : {{$key}}" class="w-full text-left p-3 text-black flex justify-between font-semibold">
              {{$row['question']}}
              <span class="text-primary" x-text="open === {{$key}} ? '▲' : '▼'"></span>
          </button>
          <div x-show="open === {{$key}}" class="p-3 text-gray-600" x-transition>
            {{$row['answer']}}
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
