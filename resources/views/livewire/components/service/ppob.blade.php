<div class="background-image ppob-bg sm:bg-gradient-to-b sm:from-violet-900 sm:to-violet-700 text-white sm:p-4 space-y-0 sm:space-y-2 rounded-lg"
x-data="{
  checkScroll() {
    this.showLeft = this.$refs.tabscontainer.scrollLeft > 0;
    this.showRight = this.$refs.tabscontainer.scrollLeft < (this.$refs.tabscontainer.scrollWidth - this.$refs.tabscontainer.clientWidth);
  },
  showLeft: false,
  showRight: true
}" x-init="checkScroll()">
  <style>
    @media (min-width: 640px) {
      .ppob-bg {
        background: url('{{url('assets/images/bg-violet.jpeg')}}')
      }
    }
  </style>
  @php
  $list = [
    [
      'image' => url('storage/online-game.png'),
      'name' => 'Game Online',
      'open' => true,
      'url' => url('topup')
    ],
    [
      'image' => url('storage/smartphone.png'),
      'name' => 'Pulsa',
      'url' => url('pulsa'),
      'open' => \App\Models\ProductInstant::where('category', 'Pulsa')->where('status', 'active')->first() ? true : false
    ],
    [
      'image' => url('storage/smartphone.png'),
      'name' => 'Paket Data',
      'open' => false,
      'url' => url('paketdata')
    ],
    [
      'image' => url('storage/lightning.png'),
      'name' => 'Listrik Pln',
      'open' => false,
      'url' => null
    ],
    [
      'image' => url('storage/e-payment.png'),
      'name' => 'E Money',
      'open' => false,
      'url' => null
    ],
    [
      'image' => url('storage/live.png'),
      'name' => 'Streaming',
      'open' => false,
      'url' => null
    ],
    [
      'image' => url('storage/gift-voucher.png'),
      'name' => 'Voucher',
      'open' => false,
      'url' => null
    ],
    // [
    //   'image' => url('storage/rekber.png'),
    //   'name' => 'Rekber Cepat',
    //   'url' => url('rekber')
    // ]
  ];
  $list = collect($list)->filter(function($item) {
    return $item['open'];
  });
  @endphp
  <div class="relative">
    <div class="flex items-center space-x-2 w-full overflow-x-auto overflow-y-hidden max-w-full hidden-scroll max-sm:p-2 sm:pb-2" x-ref="tabscontainer" @scroll="checkScroll()">
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
                <img src="{{$row['image']}}" alt="{{$row['name']}} Image" class="size-4 sm:size-5">
                @endif
                <div class="font-bold max-sm:text-xs">{{$row['name']}}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
      {{-- <div class="ml-auto text-right shrink-0">
        <button type="button" class="shrink-0">Lihat Semua</button>
      </div> --}}
    </div>
    <!-- Left Scroll Button -->
    <button
      class="absolute top-1/2 left-0 z-[2] rounded-full text-primary bg-white size-8 flex items-center justify-center shadow ring-2 ring-violet-800"
      @click="$refs.tabscontainer.scrollLeft -= 150; checkScroll();"
      x-show="showLeft"
      style="transform: translate(-32%, -61%);">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 4.5L3 12m0 0 7.5 7.5M3 12h18" />
      </svg>
    </button>
    <button
    class="absolute top-1/2 right-0 z-[2] rounded-full text-primary bg-white size-8 flex items-center justify-center shadow ring-2 ring-violet-800"
    @click="$refs.tabscontainer.scrollLeft += 150; checkScroll();"
    x-show="showRight"
    style="transform: translate(0%,-62%);">
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
