<div class="space-y-6">
  <div class="grid md:grid-cols-4 lg:grid-cols-7 gap-4 lg:gap-4">
    @foreach (\App\Models\CategoryProduct::all() as $row)
    <!-- col -->
    <div class="shrink-0">
      <a href="{{url('shop/'.$row->id)}}" class="text-decoration-none text-inherit" wire:navigate>
        <!-- card -->
        <div class="border hover:shadow-md">
          <div class="card-body text-center py-4">
            <div class="flex justify-center">
              <!-- img -->
              <img src="{{url('storage/'.$row->icon)}}" alt="{{$row->name}}" class="mb-3">
            </div>
            <!-- text -->
            <div class="truncate">{{$row->name}}</div>
          </div>
        </div>
      </a>
    </div>
    @endforeach
  </div>
  <style>
    .background-image {
      position: relative;
      overflow: hidden;
    }

    .background-image::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6); /* Adjust the rgba value to control darkness */
      z-index: 1; /* Ensure the overlay is above the background but below any content */
    }

    .background-image > * {
      position: relative;
      z-index: 2; /* Ensure content is above the overlay */
    }
  </style>
  <div class="background-image bg-gradient-to-b from-violet-900 to-violet-700 text-white p-4 space-y-2 rounded-md" style="background: url('{{url('assets/images/bg-violet.jpeg')}}')">
    @php
    $list = [
      [
        'image' => url('storage/smartphone.png'),
        'name' => 'Pulsa',
        'url' => url('pulsa')
      ],
      [
        'image' => url('storage/smartphone.png'),
        'name' => 'Paket Data',
        'url' => url('paketdata')
      ],
      [
        'image' => url('storage/lightning.png'),
        'name' => 'Listrik Pln',
        'url' => null
      ],
      [
        'image' => url('storage/e-payment.png'),
        'name' => 'E Money',
        'url' => null
      ],
      [
        'image' => url('storage/live.png'),
        'name' => 'Streaming',
        'url' => null
      ],
      [
        'image' => url('storage/gift-voucher.png'),
        'name' => 'Voucher',
        'url' => null
      ],
      [
        'image' => url('storage/online-game.png'),
        'name' => 'Game Online',
        'url' => url('topup')
      ],
      // [
      //   'image' => url('storage/rekber.png'),
      //   'name' => 'Rekber Cepat',
      //   'url' => url('rekber')
      // ]
    ];
    @endphp
    <div class="relative">
      <div class="flex items-center space-x-2 w-full overflow-x-auto overflow-y-hidden max-w-full hidden-scroll pb-2">
        @foreach ($list as $row)
        <div class="border-2 border-white py-2 px-4 rounded-md bg-violet-900 text-white hover:bg-white hover:text-primary relative cursor-pointer shadow shrink-0
        @if($row["name"] == $activeTab)
        !bg-white !text-primary
        @endif
        " @click="$wire.set('activeTab', '{{$row["name"]}}')">
          <div class="text-decoration-none text-inherit
          @if($row["name"] == $activeTab)
          tab-arrow-active
          @endif
          ">
            <!-- card -->
            <div class="">
              <div class="text-center">
                <div class="flex space-x-2 items-center">
                  <!-- img -->
                  @if ($row["image"])
                  <img src="{{$row['image']}}" alt="{{$row['name']}} Image" class="size-5">
                  @endif
                  <div class="font-bold">{{$row['name']}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
        <div class="ml-auto text-right shrink-0">
          <button type="button" class="shrink-0">Lihat Semua</button>
        </div>
      </div>
      <button class="absolute top-1/2 right-0 z-[2] rounded-full text-primary bg-white size-8 flex items-center justify-center shadow ring-2 ring-violet-800" style="transform: translate(0%,-62%);">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
        </svg>
      </button>
    </div>
    @foreach ($list as $tab)
      @if ($activeTab == $tab["name"])
      <div :key="uniqid()">
      </div>
      <livewire:is :component="'Components.Home.Services.'.str_replace(' ','',$tab['name'])" :key="uniqid()">
      @endif
    @endforeach
    @if ($activeTab == 'Rekber Cepat')
    <div class="bg-white text-gray-700 p-4 rounded-md flex flex-wrap gap-4">
      <button type="button" data-bs-toggle="modal" data-bs-target="#userModal" class="underline text-link">Silahkan Login Terlebih Dahulu</button>
    </div>
    @endif
  </div>
  {{-- <div class="flex pt-6">
    <div class="w-full flex items-center justify-between">
      <h2 class="text-md lg:text-lg flex items-center">
        <img src="{{url('assets/images/categories.png')}}" alt="" srcset="" class="w-[24px] h-[24px]">
        <div class="ms-2 font-black text-2xl">Pilihan Kategori</div>
      </h2>
    </div>
  </div> --}}
</div>
