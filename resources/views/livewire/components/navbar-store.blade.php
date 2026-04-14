<div>
  @php
  $uniqKey = uniqid();
  @endphp
  <ul class="navbar-nav flex-column" id="sideNavbar{{$uniqKey}}">
    <li class="nav-item">
      <a class="nav-link" href="{{url('/')}}" >
        <div class="d-flex align-items-center">
          <span class="nav-link-icon"><i class="bi bi-globe"></i></span>
          <span class="nav-link-text">Marketplace</span>
        </div>
      </a>
      <a class="nav-link" href="#" data-bs-toggle="collapse"
        data-bs-target="#navStore" aria-expanded="true" aria-controls="navStore">
        <div class="d-flex align-items-center">
          <span class="nav-link-icon"><i class="bi bi-house"></i></span>
          <span class="nav-link-text">Toko Saya</span>
        </div>
      </a>
      <div id="navStore" class="show !visible" data-bs-parent="#sideNavbar{{$uniqKey}}">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="{{url('/store')}}" wire:navigate>Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url('/store/profile')}}" wire:navigate>Profil</a>
          </li>
          <!-- Nav item -->
          <li class="nav-item">
            <a class="nav-link " href="{{url('/user/wallet')}}" target="_blank">Wallet</a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-bs-toggle="collapse"
        data-bs-target="#navCategoriesOrders" aria-expanded="true" aria-controls="navCategoriesOrders">
        <div class="d-flex align-items-center">
          <span class="nav-link-icon"><i class="bi bi-cart-check"></i></span>
          <span class="nav-link-text">Pesanan</span>
        </div>
      </a>
      <div id="navCategoriesOrders" class="show !visible" data-bs-parent="#sideNavbar{{$uniqKey}}">
        <ul class="nav flex-column">
          <li class="nav-item flex items-center justify-between">
            <a class="nav-link" href="{{url('/store/transaction/history')}}" wire:navigate>Daftar Pesanan</a>
            @php
            $totalUnprocessTx = \App\Models\Transaction::query()->storeTransactionQuery(['unprocessed','confirmed'])->count();
            @endphp
            <div class="text-white bg-red-600 rounded-lg p-1 text-xs min-w-[20px] flex items-center justify-center">{{$totalUnprocessTx}}</div>
          </li>
          @php
          $totalreview = \App\Models\Rating::whereUser_id(auth()->id())->count();
          @endphp
          <!-- Nav item -->
          <li class="nav-item flex items-center justify-between">
            <a class="nav-link " href="{{url('/store/transaction/rating')}}" wire:navigate>Ulasan Pembeli</a>
            <div class="text-white bg-red-600 rounded-lg p-1 text-xs min-w-[20px] flex items-center justify-center">{{$totalreview}}</div>
          </li>
          <!-- PPOB Transactions -->
          {{-- <li class="nav-item">
            <a class="nav-link " href="{{url('/store/transaction/ppob')}}" wire:navigate>Daftar Pesanan PPOB</a>
          </li> --}}
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="#" data-bs-toggle="collapse"
        data-bs-target="#navProduct" aria-expanded="true" aria-controls="navProduct">
        <div class="d-flex align-items-center">
          <span class="nav-link-icon"><i class="bi bi-box-seam"></i></span>
          <span class="nav-link-text">Dagangan</span>
        </div>
      </a>
      <div id="navProduct" class="show !visible" data-bs-parent="#sideNavbar{{$uniqKey}}">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="{{url('/store/product')}}" wire:navigate>Dagangan Saya</a>
          </li>
          <!-- Nav item -->
          <li class="nav-item">
            <a class="nav-link " href="{{url('/store/product/create')}}" wire:navigate>Buat Dagangan Baru</a>
          </li>
        </ul>
      </div>
    </li>

    <!-- PPOB Menu -->
    <li class="nav-item">
      <a class="nav-link" href="#" data-bs-toggle="collapse"
        data-bs-target="#navPPOB" aria-expanded="true" aria-controls="navPPOB">
        <div class="d-flex align-items-center">
          <span class="nav-link-icon"><i class="bi bi-phone"></i></span>
          <span class="nav-link-text">PPOB</span>
        </div>
      </a>
      <div id="navPPOB" class="show !visible" data-bs-parent="#sideNavbar{{$uniqKey}}">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="{{url('/store/ppob')}}" wire:navigate>Kelola Produk</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url('/store/ppob/add')}}" wire:navigate>Tambah Produk</a>
          </li>
        </ul>
      </div>
    </li>

    {{-- <li class="nav-item">
      <a class="nav-link" href="#" data-bs-toggle="collapse"
        data-bs-target="#navMoney" aria-expanded="true" aria-controls="navMoney">
        <div class="d-flex align-items-center">
          <span class="nav-link-icon"><i class="bi bi-bezier2"></i></span>
          <span class="nav-link-text">Integrasi</span>
        </div>
      </a>
      <div id="navMoney" class="show !visible" data-bs-parent="#sideNavbar{{$uniqKey}}">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="{{url('/store/whatsapp')}}" wire:navigate>Whatsapp</a>
          </li>
        </ul>
      </div>
    </li> --}}
  </ul>

</div>
